#!/usr/bin/env bash
#
# HTTP smoke test of the email verification on the public booking endpoints (spec 0018).
#
# With require_email_verification = 1 (restored on exit):
# - booking/register stores a Vormerkung: an appointment without customer that keeps the entered contact,
#   blocks its slot and does not reveal the manage link hash; only the verification mail goes out (Mailpit);
# - the verification link (known token set by tests/smoke/fixtures.php, opened in another "browser") assigns
#   a customer, removes the verification and redirects to the success page;
# - a second click shows the neutral "invalid" message;
# - a second booking with the verified email in that browser session is booked right away.
# All test data is removed on exit.
#
# Requires the docker stack to be up (php-fpm, nginx, mysql, mailpit) and a provider with a service.

set -uo pipefail

. "$(dirname "$0")/lib.sh"

MAILPIT="${MAILPIT_URL:-http://localhost:8025}"
CREATED_APPOINTMENTS=""
CREATED_CUSTOMERS=""
ORIGINAL_SETTING=""

cleanup() {
    fixtures setting require_email_verification "'${ORIGINAL_SETTING:-0}'" >/dev/null
    fixtures cleanup "${CREATED_APPOINTMENTS#,}" "${CREATED_CUSTOMERS#,}" >/dev/null
    fixtures purge >/dev/null
    smoke_cleanup
}
trap cleanup EXIT

# POST booking/register as a new booking. Arguments: cookie jar, start (Y-m-d H:i:s), email. Prints the HTTP
# status, body is in $TMP/body.
register() {
    local jar=$1
    curl -s -o /dev/null -c "$jar" -b "$jar" "$BASE/index.php/booking"
    local csrf
    csrf=$(awk '$6 == "csrf_cookie" { print $7 }' "$jar")

    curl -s -o "$TMP/body" -w '%{http_code}' -c "$jar" -b "$jar" \
        --data-urlencode "csrf_token=$csrf" \
        --data-urlencode "post_data[appointment][start_datetime]=$2" \
        --data-urlencode "post_data[appointment][end_datetime]=$2" \
        --data-urlencode "post_data[appointment][id_users_provider]=$PROVIDER" \
        --data-urlencode "post_data[appointment][id_services]=$SERVICE" \
        --data-urlencode "post_data[appointment][notes]=smoke verification" \
        --data-urlencode "post_data[customer][first_name]=Vera" \
        --data-urlencode "post_data[customer][last_name]=Vormerkung" \
        --data-urlencode "post_data[customer][email]=$3" \
        --data-urlencode "post_data[customer][phone_number]=0170 444" \
        --data-urlencode "post_data[customer][timezone]=UTC" \
        --data-urlencode "post_data[manage_mode]=0" \
        "$BASE/index.php/booking/register"
}

available_hours() {
    curl -s "$BASE/index.php/booking/get_available_hours" \
        --data-urlencode "provider_id=$PROVIDER" \
        --data-urlencode "service_id=$SERVICE" \
        --data-urlencode "selected_date=$DATE"
}

# GET the verification link. Arguments: cookie jar, token. Prints "<status> <redirect URL>", body in $TMP/page.
open_link() {
    curl -s -o "$TMP/page" -w '%{http_code} %{redirect_url}' -c "$1" -b "$1" "$BASE/index.php/booking/verify/$2"
}

appointment_field() { fixtures get "$1" | json "d and d['$2']"; }
contact_field() { fixtures contact "$1" | json "d and d['$2']"; }

ORIGINAL_SETTING=$(fixtures setting require_email_verification | tail -1)

SETUP=$(fixtures setup | tail -1)
[ -n "$SETUP" ] || { fail "setup test data"; exit $FAIL; }

PROVIDER=$(echo "$SETUP" | json "d['provider_id']")
SERVICE=$(echo "$SETUP" | json "d['service_id']")
DATE=$(echo "$SETUP" | json "d['date']")
TARGET_HOUR=$(echo "$SETUP" | json "d['target_hour']")
TARGET="$DATE $TARGET_HOUR:00"
SECOND_HOUR=$(echo "$SETUP" | json "d['victim']['start']")
SECOND_HOUR="${SECOND_HOUR:11:5}"
CREATED_APPOINTMENTS=",$(echo "$SETUP" | json "d['owner']['id']")"
CREATED_CUSTOMERS=",$(echo "$SETUP" | json "d['victim']['customer_id']"),$(echo "$SETUP" | json "d['owner']['customer_id']")"

# The victim's slot is the free slot for the second booking (its appointment is removed right away).
fixtures cleanup "$(echo "$SETUP" | json "d['victim']['id']")" "" >/dev/null
SECOND="$DATE $SECOND_HOUR:00"

fixtures setting require_email_verification "'1'" >/dev/null

EMAIL="smoke-verify-$$-$RANDOM@example.com"
LAPTOP="$TMP/laptop"
PHONE="$TMP/phone"

# 1. Booking on the laptop becomes a Vormerkung.
STATUS=$(register "$LAPTOP" "$TARGET" "$EMAIL")
if [ "$STATUS" = "200" ] && json "d.get('verification_pending') is True and 'appointment_hash' not in d" <"$TMP/body" | grep -q True; then
    ok "register answers 'verification pending' without manage link hash"
else
    fail "register with verification (HTTP $STATUS, $(cat "$TMP/body"))"
fi

APPOINTMENT=$(fixtures verification-by-email "$EMAIL" | json "d and d['id_appointments']")
if [ -n "$APPOINTMENT" ] && [ "$APPOINTMENT" != "None" ]; then
    CREATED_APPOINTMENTS="$CREATED_APPOINTMENTS,$APPOINTMENT"

    if [ "$(appointment_field "$APPOINTMENT" id_users_customer)" = "None" ] \
        && [ "$(appointment_field "$APPOINTMENT" start_datetime)" = "$TARGET" ] \
        && [ "$(contact_field "$APPOINTMENT" first_name)" = "Vera" ] \
        && [ "$(contact_field "$APPOINTMENT" email)" = "$EMAIL" ]; then
        ok "Vormerkung is an appointment without customer that keeps the entered contact"
    else
        fail "Vormerkung $APPOINTMENT: $(fixtures get "$APPOINTMENT"), contact $(fixtures contact "$APPOINTMENT")"
    fi
else
    fail "verification record for $EMAIL: $(fixtures verification-by-email "$EMAIL")"
    exit $FAIL
fi

if available_hours | json "'$TARGET_HOUR' in d" | grep -q False; then
    ok "Vormerkung blocks its slot"
else
    fail "slot $TARGET_HOUR still offered: $(available_hours)"
fi

MAILS=$(curl -fsS "$MAILPIT/api/v1/search?query=to:$EMAIL" 2>/dev/null)
MAIL_ID=$(echo "$MAILS" | json "d['messages'][0]['ID'] if d.get('messages') else ''")
if [ "$(echo "$MAILS" | json "len(d.get('messages', []))")" = "1" ] && curl -fsS "$MAILPIT/api/v1/message/$MAIL_ID" | json "d['HTML']" | grep -q "booking/verify/"; then
    ok "only the verification mail (with link) is sent to the entered email before the click"
else
    fail "verification mail to $EMAIL in Mailpit (message '$MAIL_ID')"
fi

# 2. The link, opened on the phone, books the appointment.
TOKEN="smoketoken$$$RANDOM"
fixtures verification-token "$APPOINTMENT" "$TOKEN" >/dev/null
HASH=$(appointment_field "$APPOINTMENT" hash)
RESULT=$(open_link "$PHONE" "$TOKEN")
CUSTOMER=$(appointment_field "$APPOINTMENT" id_users_customer)
if [[ "${RESULT%% *}" == 30? ]] && [[ "${RESULT#* }" == *"booking_confirmation/of/$HASH" ]]; then
    ok "verification link redirects to the success page"
else
    fail "verification link: $RESULT"
fi

if [ "$CUSTOMER" != "None" ] && [ "$(fixtures customer "$CUSTOMER" | json "d['email']")" = "$EMAIL" ] \
    && [ "$(fixtures verification-by-email "$EMAIL")" = "null" ]; then
    CREATED_CUSTOMERS="$CREATED_CUSTOMERS,$CUSTOMER"
    ok "verified appointment has the customer of the email, verification removed"
else
    fail "after verification: appointment $(fixtures get "$APPOINTMENT"), verification $(fixtures verification-by-email "$EMAIL")"
fi

# 3. A second click is invalid.
RESULT=$(open_link "$PHONE" "$TOKEN")
INVALID=$(fixtures lang email_verification_invalid_title | tail -1)
if [ "${RESULT%% *}" = "200" ] && grep -qF "$INVALID" "$TMP/page"; then
    ok "second click shows the neutral invalid message"
else
    fail "second click: $RESULT"
fi

# 4. A second booking with the verified email in the phone's session is booked right away.
STATUS=$(register "$PHONE" "$SECOND" "$EMAIL")
if [ "$STATUS" = "200" ] && json "'appointment_hash' in d" <"$TMP/body" | grep -q True; then
    SECOND_APPOINTMENT=$(json "d['appointment_id']" <"$TMP/body")
    CREATED_APPOINTMENTS="$CREATED_APPOINTMENTS,$SECOND_APPOINTMENT"

    if [ "$(appointment_field "$SECOND_APPOINTMENT" id_users_customer)" = "$CUSTOMER" ]; then
        ok "second booking in the verified session is booked without Vormerkung"
    else
        fail "second booking: $(fixtures get "$SECOND_APPOINTMENT")"
    fi
else
    fail "second booking in the verified session (HTTP $STATUS, $(cat "$TMP/body"))"
fi

exit $FAIL
