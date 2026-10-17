#!/usr/bin/env bash
#
# HTTP smoke test of the customer overlap rule on the public booking endpoint (spec 0023).
#
# tests/smoke/fixtures.php overlap-setup creates a second provider and a customer with one future appointment at H
# there; the first provider is free around H. Requests to booking/register with the customer's email then check:
# - an overlap by any amount with the customer's appointment is refused (partly at the start, partly at the end,
#   the request inside the appointment, the appointment inside the request), also at another provider and with
#   "any provider";
# - back-to-back before and after is booked;
# - a Manage-Link reschedule may shift the own appointment by a few minutes, but not onto another own appointment;
# - every refusal is marked as an expected booking refusal (HTTP 409, booking_refused, title).
# Email verification is switched off for the run. All test data and changed settings are restored on exit.
#
# Requires the docker stack to be up (php-fpm, nginx, mysql) and a provider with a service.

set -uo pipefail

. "$(dirname "$0")/lib.sh"

CREATED_APPOINTMENTS=""
CREATED_CUSTOMERS=""
ORIGINAL_SETTING=""

cleanup() {
    [ -z "$ORIGINAL_SETTING" ] || fixtures setting require_email_verification "'$ORIGINAL_SETTING'" >/dev/null
    fixtures cleanup "${CREATED_APPOINTMENTS#,}" "${CREATED_CUSTOMERS#,}" >/dev/null
    smoke_cleanup
}
trap cleanup EXIT

# Y-m-d H:i:s of a start moved by the given minutes.
at() { date -u -d "$1 UTC $2 minutes" '+%Y-%m-%d %H:%M:%S'; }

# POST booking/register for the customer's email. Arguments: start, provider ID (or "any-provider"), manage link
# hash ("" = new booking). Sends the end of a real booking (start + duration). Sets STATUS (HTTP status) and BOOKED
# (ID of a booked new appointment, removed on exit), body is in $TMP/body.
register() {
    local args=(
        --data-urlencode "csrf_token=smoke"
        --data-urlencode "post_data[appointment][start_datetime]=$1"
        --data-urlencode "post_data[appointment][end_datetime]=$(at "$1" "$DURATION")"
        --data-urlencode "post_data[appointment][id_users_provider]=$2"
        --data-urlencode "post_data[appointment][id_services]=$SERVICE"
        --data-urlencode "post_data[appointment][notes]=smoke overlap"
        --data-urlencode "post_data[customer][first_name]=Smoke"
        --data-urlencode "post_data[customer][last_name]=Overlap"
        --data-urlencode "post_data[customer][email]=$EMAIL"
        --data-urlencode "post_data[customer][phone_number]=0000"
        --data-urlencode "post_data[customer][timezone]=UTC"
    )
    if [ -n "$3" ]; then
        args+=(--data-urlencode "post_data[manage_mode]=1" --data-urlencode "post_data[appointment_hash]=$3")
    else
        args+=(--data-urlencode "post_data[manage_mode]=0")
    fi

    STATUS=$(curl -s -o "$TMP/body" -w '%{http_code}' -b "csrf_cookie=smoke" "${args[@]}" \
        "$BASE/index.php/booking/register")
    BOOKED=""
    if [ -z "$3" ] && [ "$STATUS" = "200" ]; then
        BOOKED=$(json "d.get('appointment_id', '')" <"$TMP/body")
        [ -n "$BOOKED" ] && CREATED_APPOINTMENTS="$CREATED_APPOINTMENTS,$BOOKED"
    fi
}

appointment_field() { fixtures get "$1" | json "d and d['$2']"; }

message() { python3 -c "import json; print(json.load(open('$TMP/body')).get('message', ''))" 2>/dev/null; }

# The last request was refused with the customer overlap message, marked as an expected refusal (HTTP 409).
refused() { [ "$1" = "409" ] && [ "$(message)" = "$ALREADY_BOOKED" ] && booking_refused; }

# Remove a wrongly booked new appointment right away, so it does not block the provider for the next cases.
drop_booked() { [ -z "$BOOKED" ] || fixtures cleanup "$BOOKED" "" >/dev/null; }

# A new booking was stored for the customer at that start.
booked() { [ "$1" = "200" ] && [ "$(fixtures get-by-customer "$CUSTOMER" "$2" | json "d and d['id']")" = "$BOOKED" ]; }

SETUP=$(fixtures overlap-setup | tail -1)
[ -n "$SETUP" ] || { fail "setup test data"; exit $FAIL; }

PROVIDER=$(echo "$SETUP" | json "d['provider_id']")
OTHER_PROVIDER=$(echo "$SETUP" | json "d['other_provider_id']")
SERVICE=$(echo "$SETUP" | json "d['service_id']")
DURATION=$(echo "$SETUP" | json "d['duration']")
OFFSET=$(echo "$SETUP" | json "d['offset']")
CUSTOMER=$(echo "$SETUP" | json "d['customer_id']")
EMAIL=$(echo "$SETUP" | json "d['email']")
OWN=$(echo "$SETUP" | json "d['appointment_id']")
OWN_HASH=$(echo "$SETUP" | json "d['hash']")
H=$(echo "$SETUP" | json "d['start']")
CREATED_APPOINTMENTS=",$OWN"
CREATED_CUSTOMERS=",$CUSTOMER,$OTHER_PROVIDER"

ORIGINAL_SETTING=$(fixtures setting require_email_verification "'0'" | tail -1)
ALREADY_BOOKED=$(fixtures lang customer_is_already_booked | tail -1)
fixtures rate-limit-reset

# 1./2. Partial overlap at the start and at the end of the own appointment (other provider).
register "$(at "$H" -"$OFFSET")" "$PROVIDER" ""
if refused "$STATUS"; then
    ok "partial overlap at the start of the own appointment refused"
else
    fail "partial overlap at the start (HTTP $STATUS, $(cat "$TMP/body"))"
    drop_booked
fi

register "$(at "$H" "$OFFSET")" "$PROVIDER" ""
if refused "$STATUS"; then
    ok "partial overlap at the end of the own appointment refused"
else
    fail "partial overlap at the end (HTTP $STATUS, $(cat "$TMP/body"))"
    drop_booked
fi

# 3./4. Containment both ways: the own appointment inside the request, the request inside the own appointment.
fixtures appointment-times "$OWN" "$(at "$H" 1)" "$(at "$H" $((DURATION - 1)))" >/dev/null
register "$H" "$PROVIDER" ""
if refused "$STATUS"; then
    ok "request containing the own appointment refused"
else
    fail "request containing the own appointment (HTTP $STATUS, $(cat "$TMP/body"))"
    drop_booked
fi

fixtures appointment-times "$OWN" "$(at "$H" -"$OFFSET")" "$(at "$H" $((DURATION + OFFSET)))" >/dev/null
register "$H" "$PROVIDER" ""
if refused "$STATUS"; then
    ok "request inside the own appointment refused"
else
    fail "request inside the own appointment (HTTP $STATUS, $(cat "$TMP/body"))"
    drop_booked
fi
fixtures appointment-times "$OWN" "$H" "$(at "$H" "$DURATION")" >/dev/null

# 5. "Any provider" picks the free provider, the overlap with the own appointment is still refused.
register "$(at "$H" -"$OFFSET")" "any-provider" ""
if refused "$STATUS"; then
    ok "partial overlap with \"any provider\" refused"
else
    fail "partial overlap with \"any provider\" (HTTP $STATUS, $(cat "$TMP/body"))"
    drop_booked
fi

# 6. Back-to-back before the own appointment is booked.
BEFORE=$(at "$H" -"$DURATION")
register "$BEFORE" "$PROVIDER" ""
if booked "$STATUS" "$BEFORE"; then
    ok "back-to-back before the own appointment booked"
    fixtures cleanup "$BOOKED" "" >/dev/null
else
    fail "back-to-back before (HTTP $STATUS, $(cat "$TMP/body"))"
fi

# 7. Manage-Link: shifting the own appointment by a few minutes overlaps only itself and is allowed.
SHIFTED=$(at "$H" "$OFFSET")
register "$SHIFTED" "$OTHER_PROVIDER" "$OWN_HASH"
if [ "$STATUS" = "200" ] && [ "$(appointment_field "$OWN" start_datetime)" = "$SHIFTED" ]; then
    ok "Manage-Link shift of the own appointment by $OFFSET minutes allowed"
else
    fail "Manage-Link shift of the own appointment (HTTP $STATUS, $(cat "$TMP/body"))"
fi
fixtures appointment-times "$OWN" "$H" "$(at "$H" "$DURATION")" >/dev/null

# 8. Back-to-back after the own appointment is booked (and stays as the second own appointment).
AFTER=$(at "$H" "$DURATION")
register "$AFTER" "$PROVIDER" ""
if booked "$STATUS" "$AFTER"; then
    ok "back-to-back after the own appointment booked"
else
    fail "back-to-back after (HTTP $STATUS, $(cat "$TMP/body"))"
fi

# 9. Manage-Link: moving the own appointment partly onto the second own appointment is refused.
ONTO=$(at "$AFTER" -"$OFFSET")
register "$ONTO" "$OTHER_PROVIDER" "$OWN_HASH"
if refused "$STATUS" && [ "$(appointment_field "$OWN" start_datetime)" = "$H" ]; then
    ok "Manage-Link move onto another own appointment refused, appointment unchanged"
else
    fail "Manage-Link move onto another own appointment (HTTP $STATUS, $(cat "$TMP/body"), start $(appointment_field "$OWN" start_datetime))"
fi

exit $FAIL
