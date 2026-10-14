#!/usr/bin/env bash
#
# HTTP smoke test of the appointment contacts on the public booking endpoint (spec 0017).
#
# Creates two customers with one future appointment each (tests/smoke/fixtures.php), then books and
# reschedules via booking/register and checks the stored appointment contact and customer records:
# - a new booking with another person's email keeps the entered contact at the appointment and only
#   takes over non-empty fields into the customer record (an empty phone keeps the stored number);
# - a reschedule stays with the original customer, even with the email of another customer, and
#   leaves that other customer unchanged;
# - a reschedule with a free new email moves the customer record to that email.
# All test data is removed on exit.
#
# Requires the docker stack to be up (php-fpm, nginx, mysql) and a provider with a service.

set -uo pipefail

. "$(dirname "$0")/lib.sh"

CREATED_APPOINTMENTS=""
CREATED_CUSTOMERS=""

cleanup() {
    fixtures cleanup "${CREATED_APPOINTMENTS#,}" "${CREATED_CUSTOMERS#,}" >/dev/null
    smoke_cleanup
}
trap cleanup EXIT

# POST booking/register. Arguments: start (Y-m-d H:i:s), hash ("" = new booking), first name, last name,
# email, phone. Prints the HTTP status, body is in $TMP/body.
register() {
    local args=(
        --data-urlencode "csrf_token=smoke"
        --data-urlencode "post_data[appointment][start_datetime]=$1"
        --data-urlencode "post_data[appointment][end_datetime]=$1"
        --data-urlencode "post_data[appointment][id_users_provider]=$PROVIDER"
        --data-urlencode "post_data[appointment][id_services]=$SERVICE"
        --data-urlencode "post_data[appointment][notes]=smoke contacts"
        --data-urlencode "post_data[customer][first_name]=$3"
        --data-urlencode "post_data[customer][last_name]=$4"
        --data-urlencode "post_data[customer][email]=$5"
        --data-urlencode "post_data[customer][phone_number]=$6"
        --data-urlencode "post_data[customer][timezone]=UTC"
    )
    if [ -n "$2" ]; then
        args+=(--data-urlencode "post_data[manage_mode]=1" --data-urlencode "post_data[appointment_hash]=$2")
    else
        args+=(--data-urlencode "post_data[manage_mode]=0")
    fi

    curl -s -o "$TMP/body" -w '%{http_code}' -b "csrf_cookie=smoke" "${args[@]}" "$BASE/index.php/booking/register"
}

customer_field() { fixtures customer "$1" | json "d['$2']"; }
contact_field() { fixtures contact "$1" | json "d and d['$2']"; }
appointment_field() { fixtures get "$1" | json "d and d['$2']"; }

SETUP=$(fixtures setup | tail -1)
[ -n "$SETUP" ] || { fail "setup test data"; exit $FAIL; }

PROVIDER=$(echo "$SETUP" | json "d['provider_id']")
SERVICE=$(echo "$SETUP" | json "d['service_id']")
DATE=$(echo "$SETUP" | json "d['date']")
TARGET="$DATE $(echo "$SETUP" | json "d['target_hour']"):00"
ANNA_APPOINTMENT=$(echo "$SETUP" | json "d['victim']['id']")
ANNA=$(echo "$SETUP" | json "d['victim']['customer_id']")
BERND_APPOINTMENT=$(echo "$SETUP" | json "d['owner']['id']")
BERND_HASH=$(echo "$SETUP" | json "d['owner']['hash']")
BERND_START=$(echo "$SETUP" | json "d['owner']['start']")
BERND=$(echo "$SETUP" | json "d['owner']['customer_id']")
CREATED_APPOINTMENTS=",$ANNA_APPOINTMENT,$BERND_APPOINTMENT"
CREATED_CUSTOMERS=",$ANNA,$BERND"

ANNA_EMAIL=$(customer_field "$ANNA" email)
ANNA_PHONE=$(customer_field "$ANNA" phone_number)
BERND_EMAIL=$(customer_field "$BERND" email)

# 1. Lena books with Anna's email and leaves the phone empty.
STATUS=$(register "$TARGET" "" "Lena" "Neu" "$ANNA_EMAIL" "")
if [ "$STATUS" = "200" ] && json "'appointment_id' in d" <"$TMP/body" | grep -q True; then
    LENA_APPOINTMENT=$(json "d['appointment_id']" <"$TMP/body")
    CREATED_APPOINTMENTS="$CREATED_APPOINTMENTS,$LENA_APPOINTMENT"

    if [ "$(appointment_field "$LENA_APPOINTMENT" id_users_customer)" = "$ANNA" ] \
        && [ "$(contact_field "$LENA_APPOINTMENT" first_name)" = "Lena" ] \
        && [ "$(contact_field "$LENA_APPOINTMENT" last_name)" = "Neu" ] \
        && [ "$(contact_field "$LENA_APPOINTMENT" email)" = "$ANNA_EMAIL" ] \
        && [ "$(contact_field "$LENA_APPOINTMENT" phone_number)" = "None" ]; then
        ok "booking with another person's email keeps the entered contact at the appointment"
    else
        fail "appointment contact of booking $LENA_APPOINTMENT: $(fixtures contact "$LENA_APPOINTMENT")"
    fi

    if [ "$(customer_field "$ANNA" first_name)" = "Lena" ] && [ "$(customer_field "$ANNA" phone_number)" = "$ANNA_PHONE" ]; then
        ok "customer record takes over non-empty fields, empty phone keeps the stored number"
    else
        fail "customer record after booking: $(fixtures customer "$ANNA")"
    fi

    # Free the target slot again for the reschedule checks.
    fixtures cleanup "$LENA_APPOINTMENT" "" >/dev/null
else
    fail "booking with another person's email (HTTP $STATUS, $(cat "$TMP/body"))"
fi

# 2. Bernd reschedules and enters Anna's email.
ANNA_BEFORE=$(fixtures customer "$ANNA")
STATUS=$(register "$TARGET" "$BERND_HASH" "Bernd" "Umbucher" "$ANNA_EMAIL" "0170 333")
if [ "$STATUS" = "200" ] && [ "$(appointment_field "$BERND_APPOINTMENT" start_datetime)" = "$TARGET" ]; then
    if [ "$(appointment_field "$BERND_APPOINTMENT" id_users_customer)" = "$BERND" ] \
        && [ "$(fixtures customer "$ANNA")" = "$ANNA_BEFORE" ] \
        && [ "$(customer_field "$BERND" email)" = "$BERND_EMAIL" ]; then
        ok "reschedule with another customer's email stays with the original customer, other customer unchanged"
    else
        fail "reschedule with another customer's email: appointment customer $(appointment_field "$BERND_APPOINTMENT" id_users_customer), Bernd $(fixtures customer "$BERND")"
    fi

    if [ "$(contact_field "$BERND_APPOINTMENT" last_name)" = "Umbucher" ] \
        && [ "$(contact_field "$BERND_APPOINTMENT" email)" = "$ANNA_EMAIL" ] \
        && [ "$(contact_field "$BERND_APPOINTMENT" phone_number)" = "0170 333" ]; then
        ok "rescheduled appointment keeps the new entries as contact"
    else
        fail "contact of rescheduled appointment: $(fixtures contact "$BERND_APPOINTMENT")"
    fi
else
    fail "reschedule with another customer's email (HTTP $STATUS, $(cat "$TMP/body"))"
fi

# 3. Bernd reschedules back and enters a free new email.
NEW_EMAIL="smoke-bernd-new-$$@example.com"
STATUS=$(register "$BERND_START" "$BERND_HASH" "Bernd" "Umbucher" "$NEW_EMAIL" "0170 333")
if [ "$STATUS" = "200" ] && [ "$(appointment_field "$BERND_APPOINTMENT" id_users_customer)" = "$BERND" ] \
    && [ "$(customer_field "$BERND" email)" = "$NEW_EMAIL" ]; then
    ok "reschedule with a free new email moves the customer record to it"
else
    fail "reschedule with a free new email (HTTP $STATUS, $(cat "$TMP/body"), Bernd $(fixtures customer "$BERND"))"
fi

exit $FAIL
