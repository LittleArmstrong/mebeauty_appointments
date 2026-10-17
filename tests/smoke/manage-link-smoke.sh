#!/usr/bin/env bash
#
# HTTP smoke test of the manage-link authorization on the booking endpoints (spec 0009).
#
# Creates two customers with one future appointment each (tests/smoke/fixtures.php),
# then sends crafted requests to booking/register and checks the stored data afterwards:
# a record may only be changed with the manage-link hash that belongs to it, and the
# manage deadline is checked again on submit. All test data and changed settings are
# restored on exit.
#
# Requires the docker stack to be up (php-fpm, nginx, mysql) and a provider with a service.

set -uo pipefail

. "$(dirname "$0")/lib.sh"

CREATED_APPOINTMENTS=""
CREATED_CUSTOMERS=""
RESTORE_SETTINGS=()

set_setting() {
    local old
    old=$(fixtures setting "$1" "'$2'" | tail -1)
    RESTORE_SETTINGS+=("$1" "$old")
}

cleanup() {
    local i
    for ((i = 0; i < ${#RESTORE_SETTINGS[@]}; i += 2)); do
        fixtures setting "${RESTORE_SETTINGS[i]}" "'${RESTORE_SETTINGS[i + 1]}'" >/dev/null
    done
    fixtures cleanup "${CREATED_APPOINTMENTS#,}" "${CREATED_CUSTOMERS#,}" >/dev/null
    smoke_cleanup
}
trap cleanup EXIT

# POST booking/register. Arguments: start (Y-m-d H:i:s), manage_mode (0/1), appointment id ("" = none),
# customer id ("" = none), hash ("" = none), email. Prints the HTTP status, body is in $TMP/body.
register() {
    local args=(
        --data-urlencode "csrf_token=smoke"
        --data-urlencode "post_data[appointment][start_datetime]=$1"
        --data-urlencode "post_data[appointment][end_datetime]=$1"
        --data-urlencode "post_data[appointment][id_users_provider]=$PROVIDER"
        --data-urlencode "post_data[appointment][id_services]=$SERVICE"
        --data-urlencode "post_data[appointment][notes]=smoke request"
        --data-urlencode "post_data[customer][first_name]=Smoke"
        --data-urlencode "post_data[customer][last_name]=Attacker"
        --data-urlencode "post_data[customer][email]=$6"
        --data-urlencode "post_data[customer][phone_number]=0000"
        --data-urlencode "post_data[customer][timezone]=UTC"
        --data-urlencode "post_data[manage_mode]=$2"
    )
    [ -n "$3" ] && args+=(--data-urlencode "post_data[appointment][id]=$3")
    [ -n "$4" ] && args+=(--data-urlencode "post_data[customer][id]=$4")
    [ -n "$5" ] && args+=(--data-urlencode "post_data[appointment_hash]=$5")

    curl -s -o "$TMP/body" -w '%{http_code}' -b "csrf_cookie=smoke" "${args[@]}" "$BASE/index.php/booking/register"
}

appointment_field() { fixtures get "$1" | json "d and d['$2']"; }

# Error message of the last response (decoded JSON), and whether it starts with a translation.
message() { python3 -c "import json; print(json.load(open('$TMP/body')).get('message', ''))" 2>/dev/null; }
message_is() {
    local expected
    expected=$(fixtures lang "$1" | tail -1)
    expected="${expected%%\{\$limit\}*}"
    [[ "$(message)" == "$expected"* ]]
}

SETUP=$(fixtures setup | tail -1)
[ -n "$SETUP" ] || { fail "setup test data"; exit $FAIL; }

PROVIDER=$(echo "$SETUP" | json "d['provider_id']")
SERVICE=$(echo "$SETUP" | json "d['service_id']")
DATE=$(echo "$SETUP" | json "d['date']")
TARGET="$DATE $(echo "$SETUP" | json "d['target_hour']"):00"
VICTIM_ID=$(echo "$SETUP" | json "d['victim']['id']")
VICTIM_START=$(echo "$SETUP" | json "d['victim']['start']")
VICTIM_CUSTOMER=$(echo "$SETUP" | json "d['victim']['customer_id']")
OWNER_ID=$(echo "$SETUP" | json "d['owner']['id']")
OWNER_HASH=$(echo "$SETUP" | json "d['owner']['hash']")
OWNER_CUSTOMER=$(echo "$SETUP" | json "d['owner']['customer_id']")
CREATED_APPOINTMENTS=",$VICTIM_ID,$OWNER_ID"
CREATED_CUSTOMERS=",$VICTIM_CUSTOMER,$OWNER_CUSTOMER"

VICTIM_EMAIL_BEFORE=$(fixtures customer "$VICTIM_CUSTOMER" | json "d['email']")

# 1. Foreign appointment ID without hash and without manage mode: no change, no new booking.
STATUS=$(register "$TARGET" 0 "$VICTIM_ID" "" "" "smoke-new-$$@example.com")
if [ "$STATUS" != "200" ] && message_is appointment_not_found \
    && [ "$(appointment_field "$VICTIM_ID" start_datetime)" = "$VICTIM_START" ]; then
    ok "foreign appointment id without hash rejected, appointment unchanged"
else
    fail "foreign appointment id without hash (HTTP $STATUS, $(cat "$TMP/body"))"
fi

# 2. Manage mode with a valid hash that belongs to another appointment.
STATUS=$(register "$TARGET" 1 "$VICTIM_ID" "" "$OWNER_HASH" "smoke-new-$$@example.com")
if [ "$STATUS" != "200" ] && message_is appointment_not_found \
    && [ "$(appointment_field "$VICTIM_ID" start_datetime)" = "$VICTIM_START" ]; then
    ok "hash of another appointment rejected"
else
    fail "hash of another appointment (HTTP $STATUS, $(cat "$TMP/body"))"
fi

# 3. Manage mode without hash.
STATUS=$(register "$TARGET" 1 "" "" "" "smoke-new-$$@example.com")
if [ "$STATUS" != "200" ] && message_is appointment_not_found; then
    ok "manage mode without hash rejected (no silent new booking)"
else
    fail "manage mode without hash (HTTP $STATUS, $(cat "$TMP/body"))"
    [ "$STATUS" = "200" ] && CREATED_APPOINTMENTS="$CREATED_APPOINTMENTS,$(json "d['appointment_id']" <"$TMP/body")"
fi

# 4. New booking that sends a foreign customer ID: booking is created, foreign customer unchanged.
NEW_EMAIL="smoke-new-$$@example.com"
STATUS=$(register "$TARGET" 0 "" "$VICTIM_CUSTOMER" "" "$NEW_EMAIL")
if [ "$STATUS" = "200" ]; then
    NEW_ID=$(json "d['appointment_id']" <"$TMP/body")
    NEW_CUSTOMER=$(appointment_field "$NEW_ID" id_users_customer)
    CREATED_APPOINTMENTS="$CREATED_APPOINTMENTS,$NEW_ID"
    [ "$NEW_CUSTOMER" != "$VICTIM_CUSTOMER" ] && CREATED_CUSTOMERS="$CREATED_CUSTOMERS,$NEW_CUSTOMER"
    if [ "$NEW_CUSTOMER" != "$VICTIM_CUSTOMER" ] \
        && [ "$(fixtures customer "$VICTIM_CUSTOMER" | json "d['email']")" = "$VICTIM_EMAIL_BEFORE" ]; then
        ok "new booking with foreign customer id creates own customer, foreign customer unchanged"
    else
        fail "new booking with foreign customer id touched customer $VICTIM_CUSTOMER"
    fi
    # Free the target slot again for the following reschedule checks.
    fixtures cleanup "$NEW_ID" "" >/dev/null
else
    fail "new booking (HTTP $STATUS, $(cat "$TMP/body"))"
fi

# 5. Manage deadline passed: reschedule of the owner's appointment is rejected.
set_setting manage_deadline_mode minutes
set_setting manage_deadline_minutes 100000
STATUS=$(register "$TARGET" 1 "$OWNER_ID" "" "$OWNER_HASH" "smoke-owner-$$@example.com")
if [ "$STATUS" != "200" ] && message_is manage_locked_message_minutes \
    && [ "$(appointment_field "$OWNER_ID" start_datetime)" != "$TARGET" ]; then
    ok "reschedule after manage deadline rejected"
else
    fail "reschedule after manage deadline (HTTP $STATUS, $(cat "$TMP/body"))"
fi
fixtures setting manage_deadline_minutes "'0'" >/dev/null

# 6. Target slot after the booking deadline: rejected although the manage deadline is open.
set_setting booking_deadline_mode fixed_time
set_setting booking_deadline_days 365
STATUS=$(register "$TARGET" 1 "$OWNER_ID" "" "$OWNER_HASH" "smoke-owner-$$@example.com")
if [ "$STATUS" = "409" ] && message_is requested_hour_is_unavailable && booking_refused \
    && [ "$(appointment_field "$OWNER_ID" start_datetime)" != "$TARGET" ]; then
    ok "target slot after booking deadline rejected as expected booking refusal"
else
    fail "target slot after booking deadline (HTTP $STATUS, $(cat "$TMP/body"))"
fi
fixtures setting booking_deadline_mode "'minutes'" >/dev/null

# 7. Valid reschedule within all deadlines.
STATUS=$(register "$TARGET" 1 "$OWNER_ID" "" "$OWNER_HASH" "smoke-owner-$$@example.com")
if [ "$STATUS" = "200" ] && [ "$(appointment_field "$OWNER_ID" start_datetime)" = "$TARGET" ] \
    && [ "$(appointment_field "$OWNER_ID" id_users_customer)" = "$OWNER_CUSTOMER" ]; then
    ok "valid reschedule with matching hash"
else
    fail "valid reschedule (HTTP $STATUS, $(cat "$TMP/body"))"
fi

# 8./9. Available hours in manage mode: a foreign appointment ID without hash is not excluded,
#       the own appointment (identified by hash) is.
available_hours() {
    curl -s "$BASE/index.php/booking/get_available_hours" \
        --data-urlencode "provider_id=$PROVIDER" \
        --data-urlencode "service_id=$SERVICE" \
        --data-urlencode "selected_date=$DATE" \
        --data-urlencode "manage_mode=1" "$@"
}
VICTIM_HOUR="${VICTIM_START:11:5}"
OWNER_HOUR="${TARGET:11:5}"

HOURS=$(available_hours --data-urlencode "appointment_id=$VICTIM_ID")
if grep -q "\"$VICTIM_HOUR\"" <<<"$HOURS"; then
    fail "available hours: foreign appointment $VICTIM_ID excluded via appointment_id"
else
    ok "available hours: foreign appointment id is ignored (slot $VICTIM_HOUR stays taken)"
fi

HOURS=$(available_hours --data-urlencode "appointment_hash=$OWNER_HASH")
if grep -q "\"$OWNER_HOUR\"" <<<"$HOURS"; then
    ok "available hours: own appointment excluded via manage hash (slot $OWNER_HOUR free)"
else
    fail "available hours: own appointment not excluded via manage hash"
fi

exit $FAIL
