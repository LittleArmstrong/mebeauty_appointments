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
# - a second booking with the verified email in that browser session is booked right away;
# - a backdated (expired) Vormerkung frees its slot on the next availability request; a late click books it again
#   while the slot is free and shows "expired" once the slot is taken; after 24 hours the cleanup deletes the
#   restore data and the link is invalid;
# - "erneut senden" (booking/resend_verification, Vormerkung of the browser session) waits 60 seconds after the
#   last mail, replaces the link, stops after 5 mails per email and hour (also for a new Vormerkung) and neither
#   reveals the manage link hash nor needs or accepts an email or ID from the client.
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

# POST booking/resend_verification. Argument: cookie jar. Prints the HTTP status, body is in $TMP/resend.
resend() {
    local csrf
    csrf=$(awk '$6 == "csrf_cookie" { print $7 }' "$1")
    curl -s -o "$TMP/resend" -w '%{http_code}' -c "$1" -b "$1" --data-urlencode "csrf_token=$csrf" \
        "$BASE/index.php/booking/resend_verification"
}

mail_count() { curl -fsS "$MAILPIT/api/v1/search?query=to:$1" 2>/dev/null | json "len(d.get('messages', []))"; }

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

# 5. Expiry: a Vormerkung backdated by 11 minutes frees its slot (lazy cleanup before the availability request).
fixtures cleanup "$SECOND_APPOINTMENT" "" >/dev/null
LATE_EMAIL="smoke-verify-late-$$-$RANDOM@example.com"
register "$TMP/late" "$SECOND" "$LATE_EMAIL" >/dev/null
LATE_APPOINTMENT=$(fixtures verification-by-email "$LATE_EMAIL" | json "d and d['id_appointments']")
CREATED_APPOINTMENTS="$CREATED_APPOINTMENTS,$LATE_APPOINTMENT"
LATE_TOKEN="smokelate$$$RANDOM"
fixtures verification-token "$LATE_APPOINTMENT" "$LATE_TOKEN" 11 >/dev/null

if available_hours | json "'$SECOND_HOUR' in d" | grep -q True \
    && [ "$(fixtures get "$LATE_APPOINTMENT")" = "null" ] \
    && [ "$(fixtures verification-by-email "$LATE_EMAIL" | json "d and d['id_appointments']")" = "None" ]; then
    ok "expired Vormerkung is deleted and frees its slot, restore data stays"
else
    fail "after expiry: slots $(available_hours), appointment $(fixtures get "$LATE_APPOINTMENT"), verification $(fixtures verification-by-email "$LATE_EMAIL")"
fi

# 6. Late click while the slot is free: booked again with the customer of the email.
RESULT=$(open_link "$TMP/late-phone" "$LATE_TOKEN")
LATE_HASH="${RESULT##*booking_confirmation/of/}"
LATE_BOOKED=$(fixtures appointment-by-hash "$LATE_HASH")
LATE_CUSTOMER=$(echo "$LATE_BOOKED" | json "d and d['id_users_customer']")
if [[ "${RESULT%% *}" == 30? ]] && [[ "$RESULT" == *"booking_confirmation/of/"* ]] \
    && [ "$(echo "$LATE_BOOKED" | json "d and d['start_datetime']")" = "$SECOND" ] \
    && [ "$LATE_CUSTOMER" != "None" ] \
    && [ "$(fixtures customer "$LATE_CUSTOMER" | json "d['email']")" = "$LATE_EMAIL" ] \
    && [ "$(contact_field "$(echo "$LATE_BOOKED" | json "d['id']")" first_name)" = "Vera" ] \
    && [ "$(fixtures verification-by-email "$LATE_EMAIL")" = "null" ]; then
    CREATED_APPOINTMENTS="$CREATED_APPOINTMENTS,$(echo "$LATE_BOOKED" | json "d['id']")"
    CREATED_CUSTOMERS="$CREATED_CUSTOMERS,$LATE_CUSTOMER"
    ok "late click with a free slot books the appointment again"
else
    fail "late click with a free slot: $RESULT, appointment $LATE_BOOKED, verification $(fixtures verification-by-email "$LATE_EMAIL")"
fi

# 7. Late click after the slot was taken: "expired" page. The slot is taken by a booking in the verified phone
#    session, whose submit cleans up the expired Vormerkung first.
fixtures cleanup "$(echo "$LATE_BOOKED" | json "d and d['id']")" "" >/dev/null
TAKEN_EMAIL="smoke-verify-taken-$$-$RANDOM@example.com"
register "$TMP/taken" "$SECOND" "$TAKEN_EMAIL" >/dev/null
TAKEN_APPOINTMENT=$(fixtures verification-by-email "$TAKEN_EMAIL" | json "d and d['id_appointments']")
CREATED_APPOINTMENTS="$CREATED_APPOINTMENTS,$TAKEN_APPOINTMENT"
TAKEN_TOKEN="smoketaken$$$RANDOM"
fixtures verification-token "$TAKEN_APPOINTMENT" "$TAKEN_TOKEN" 11 >/dev/null

STATUS=$(register "$PHONE" "$SECOND" "$EMAIL")
if [ "$STATUS" = "200" ] && json "'appointment_hash' in d" <"$TMP/body" | grep -q True; then
    SLOT_TAKER=$(json "d['appointment_id']" <"$TMP/body")
    CREATED_APPOINTMENTS="$CREATED_APPOINTMENTS,$SLOT_TAKER"
    ok "submit cleans up the expired Vormerkung, the slot can be booked"
else
    fail "booking the slot of an expired Vormerkung (HTTP $STATUS, $(cat "$TMP/body"))"
fi

RESULT=$(open_link "$TMP/taken-phone" "$TAKEN_TOKEN")
EXPIRED=$(fixtures lang email_verification_expired_title | tail -1)
if [ "${RESULT%% *}" = "200" ] && grep -qF "$EXPIRED" "$TMP/page" \
    && [ "$(fixtures verification-by-email "$TAKEN_EMAIL" | json "d and d['id_appointments']")" = "None" ]; then
    ok "late click with a taken slot shows the expired page"
else
    fail "late click with a taken slot: $RESULT, verification $(fixtures verification-by-email "$TAKEN_EMAIL")"
fi

# 8. After 24 hours the cleanup (as in console cleanup) deletes the restore data and the link is invalid.
fixtures verification-token "$TAKEN_EMAIL" "$TAKEN_TOKEN" 1440 >/dev/null
fixtures cleanup-verifications >/dev/null
RESULT=$(open_link "$TMP/taken-phone" "$TAKEN_TOKEN")
if [ "$(fixtures verification-by-email "$TAKEN_EMAIL")" = "null" ] && [ "${RESULT%% *}" = "200" ] \
    && grep -qF "$INVALID" "$TMP/page"; then
    ok "after 24 hours the cleanup deletes the restore data, the link is invalid"
else
    fail "after 24 hours: verification $(fixtures verification-by-email "$TAKEN_EMAIL"), link $RESULT"
fi

# 9. "Erneut senden": the Vormerkung comes from the browser session, the client sends no email or ID.
fixtures cleanup "${SLOT_TAKER:-}" "" >/dev/null
RESEND_EMAIL="smoke-verify-resend-$$-$RANDOM@example.com"
RESEND_JAR="$TMP/resend-laptop"
register "$RESEND_JAR" "$SECOND" "$RESEND_EMAIL" >/dev/null
RESEND_APPOINTMENT=$(fixtures verification-by-email "$RESEND_EMAIL" | json "d and d['id_appointments']")
CREATED_APPOINTMENTS="$CREATED_APPOINTMENTS,$RESEND_APPOINTMENT"

STATUS=$(resend "$RESEND_JAR")
if [ "$STATUS" = "200" ] && json "d['sent'] is False and 0 < d['wait_seconds'] <= 60" <"$TMP/resend" | grep -q True \
    && [ "$(mail_count "$RESEND_EMAIL")" = "1" ]; then
    ok "resend within 60 seconds asks to wait and sends no mail"
else
    fail "resend within 60 seconds (HTTP $STATUS, $(cat "$TMP/resend"), mails $(mail_count "$RESEND_EMAIL"))"
fi

OLD_TOKEN="smokeold$$$RANDOM"
fixtures verification-token "$RESEND_APPOINTMENT" "$OLD_TOKEN" >/dev/null
fixtures limit-backdate "$RESEND_EMAIL" 61 >/dev/null
STATUS=$(resend "$RESEND_JAR")
RESENT=$(fixtures verification-by-email "$RESEND_EMAIL")
if [ "$STATUS" = "200" ] && json "d['sent'] is True and 'hash' not in json.dumps(d)" <"$TMP/resend" | grep -q True \
    && [ "$(mail_count "$RESEND_EMAIL")" = "2" ] \
    && [ "$(echo "$RESENT" | json "d['id_appointments'] == $RESEND_APPOINTMENT and d['send_count'] == 2")" = "True" ] \
    && [ "$(echo "$RESENT" | json "d['token_hash']")" != "$(printf '%s' "$OLD_TOKEN" | sha256sum | cut -d' ' -f1)" ]; then
    ok "resend after 60 seconds sends a new link for the same Vormerkung"
else
    fail "resend after 60 seconds (HTTP $STATUS, $(cat "$TMP/resend"), mails $(mail_count "$RESEND_EMAIL"), $RESENT)"
fi

RESULT=$(open_link "$TMP/resend-phone" "$OLD_TOKEN")
if [ "${RESULT%% *}" = "200" ] && grep -qF "$INVALID" "$TMP/page" \
    && [ "$(fixtures get "$RESEND_APPOINTMENT" | json "d and d['id_users_customer']")" = "None" ]; then
    ok "the previous link is invalid after a resend"
else
    fail "previous link after a resend: $RESULT, appointment $(fixtures get "$RESEND_APPOINTMENT")"
fi

for _ in 1 2 3; do
    fixtures limit-backdate "$RESEND_EMAIL" 61 >/dev/null
    resend "$RESEND_JAR" >/dev/null
done
fixtures limit-backdate "$RESEND_EMAIL" 61 >/dev/null
STATUS=$(resend "$RESEND_JAR")
LIMIT_TEXT=$(fixtures lang email_verification_limit_reached | tail -1)
if [ "$STATUS" = "200" ] && json "d['sent'] is False and d['wait_seconds'] > 60" <"$TMP/resend" | grep -q True \
    && [ "$(json "d['message']" <"$TMP/resend")" = "$LIMIT_TEXT" ] \
    && [ "$(mail_count "$RESEND_EMAIL")" = "5" ]; then
    ok "the sixth mail within the hour is refused"
else
    fail "sixth mail (HTTP $STATUS, $(cat "$TMP/resend"), mails $(mail_count "$RESEND_EMAIL"))"
fi

# The hourly limit also refuses a new Vormerkung with that email (another browser, the confirmed first slot freed).
fixtures cleanup "$APPOINTMENT" "" >/dev/null
STATUS=$(register "$TMP/resend-other" "$TARGET" "$RESEND_EMAIL")
if [ "$STATUS" != "200" ] && [ "$(json "d.get('message')" <"$TMP/body")" = "$LIMIT_TEXT" ] \
    && [ "$(fixtures verification-by-email "$RESEND_EMAIL" | json "d['id_appointments']")" = "$RESEND_APPOINTMENT" ] \
    && [ "$(mail_count "$RESEND_EMAIL")" = "5" ] && available_hours | json "'$TARGET_HOUR' in d" | grep -q True; then
    ok "the hourly limit also refuses a new Vormerkung with the same email"
else
    fail "new Vormerkung over the limit (HTTP $STATUS, $(cat "$TMP/body"), mails $(mail_count "$RESEND_EMAIL"))"
fi

# A browser without Vormerkung gets the neutral answer and no mail goes out.
curl -s -o /dev/null -c "$TMP/stranger" -b "$TMP/stranger" "$BASE/index.php/booking"
STATUS=$(resend "$TMP/stranger")
if [ "$STATUS" = "200" ] && json "d['sent'] is False and d['wait_seconds'] == 0" <"$TMP/resend" | grep -q True \
    && [ "$(json "d['message']" <"$TMP/resend")" = "$(fixtures lang email_verification_resend_unavailable | tail -1)" ]; then
    ok "resend without Vormerkung in the browser session sends nothing"
else
    fail "resend without Vormerkung (HTTP $STATUS, $(cat "$TMP/resend"))"
fi

exit $FAIL
