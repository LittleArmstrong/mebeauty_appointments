#!/usr/bin/env bash
#
# HTTP smoke test of the email verification on the public booking endpoints (spec 0018).
#
# With require_email_verification = 1 (restored on exit):
# - booking/register stores a Vormerkung: an appointment without customer that keeps the entered contact,
#   blocks its slot and does not reveal the manage link hash; only the verification mail goes out (Mailpit);
# - opening the verification link (known token set by tests/smoke/fixtures.php, opened in another "browser") only
#   shows a confirm button and changes nothing (mail scanners open links); the button (POST with CSRF token) assigns
#   a customer, removes the verification and redirects to the success page;
# - a second confirmation shows the neutral "invalid" message;
# - a second booking with the verified email in that browser session is booked right away;
# - a backdated (expired) Vormerkung frees its slot on the next availability request; a late click books it again
#   while the slot is free and shows "expired" once the slot is taken; after 24 hours the cleanup deletes the
#   restore data and the link is invalid; a Vormerkung deleted another way (staff, API) is not restorable;
# - "erneut senden" (booking/resend_verification, Vormerkung of the browser session) waits 60 seconds after the
#   last mail, replaces the link, stops after 5 mails per email and hour (also for a new Vormerkung) and neither
#   reveals the manage link hash nor needs or accepts an email or ID from the client;
# - a booking with the email of a customer who is booked at that time answers like any other (no hint that the
#   email is known); its confirmation shows a neutral page and drops the Vormerkung;
# - a reschedule with a changed email applies the new time right away, keeps the old email in the appointment
#   contact and the customer record (the reschedule mail goes there) and sends a link to the new email; an
#   expired link changes nothing, a valid one takes over the email (customer record only while no other customer
#   has it); a reschedule without email change needs no link; a new email over its hourly mail limit keeps the
#   old one, the reschedule still applies.
# All test data is removed on exit.
#
# Requires the docker stack to be up (php-fpm, nginx, mysql, mailpit) and a provider with a service.

set -uo pipefail

. "$(dirname "$0")/lib.sh"

MAILPIT="${MAILPIT_URL:-http://localhost:8025}"
CREATED_APPOINTMENTS=""
CREATED_CUSTOMERS=""
ORIGINAL_SETTING=""
ORIGINAL_ATTENDANTS=""

cleanup() {
    fixtures setting require_email_verification "'${ORIGINAL_SETTING:-0}'" >/dev/null
    [ -z "$ORIGINAL_ATTENDANTS" ] || fixtures service-attendants "$SERVICE" "$ORIGINAL_ATTENDANTS" >/dev/null
    fixtures cleanup "${CREATED_APPOINTMENTS#,}" "${CREATED_CUSTOMERS#,}" >/dev/null
    fixtures purge >/dev/null
    smoke_cleanup
}
trap cleanup EXIT

# POST booking/register. Arguments: cookie jar, start (Y-m-d H:i:s), email, manage link hash (optional: a
# reschedule). Prints the HTTP status, body is in $TMP/body.
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
        --data-urlencode "post_data[manage_mode]=$([ -n "${4:-}" ] && echo 1 || echo 0)" \
        --data-urlencode "post_data[appointment_hash]=${4:-}" \
        "$BASE/index.php/booking/register"
}

available_hours() {
    curl -s "$BASE/index.php/booking/get_available_hours" \
        --data-urlencode "provider_id=$PROVIDER" \
        --data-urlencode "service_id=$SERVICE" \
        --data-urlencode "selected_date=$DATE"
}

# GET the verification link (shows the confirm button). Arguments: cookie jar, token. Prints the HTTP status, body in
# $TMP/page.
open_link() {
    curl -s -o "$TMP/page" -w '%{http_code}' -c "$1" -b "$1" "$BASE/index.php/booking/verify/$2"
}

# Open the verification link and press its confirm button (POST with the CSRF token of the page's cookie).
# Arguments: cookie jar, token. Prints "<status> <redirect URL>" of the button, body in $TMP/page.
confirm_link() {
    open_link "$1" "$2" >/dev/null
    local csrf
    csrf=$(awk '$6 == "csrf_cookie" { print $7 }' "$1")
    curl -s -o "$TMP/page" -w '%{http_code} %{redirect_url}' -c "$1" -b "$1" \
        --data-urlencode "csrf_token=$csrf" --data-urlencode "token=$2" \
        "$BASE/index.php/booking/confirm_verification"
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
OWNER_APPOINTMENT=$(echo "$SETUP" | json "d['owner']['id']")
OWNER_HASH=$(echo "$SETUP" | json "d['owner']['hash']")
OWNER_START=$(echo "$SETUP" | json "d['owner']['start']")
OWNER=$(echo "$SETUP" | json "d['owner']['customer_id']")
VICTIM=$(echo "$SETUP" | json "d['victim']['customer_id']")
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

# 2. The link, opened on the phone, shows the confirm button; the button books the appointment.
TOKEN="smoketoken$$$RANDOM"
fixtures verification-token "$APPOINTMENT" "$TOKEN" >/dev/null
HASH=$(appointment_field "$APPOINTMENT" hash)
STATUS=$(open_link "$PHONE" "$TOKEN")
if [ "$STATUS" = "200" ] && grep -q 'booking/confirm_verification' "$TMP/page" && grep -qF "value=\"$TOKEN\"" "$TMP/page" \
    && [ "$(appointment_field "$APPOINTMENT" id_users_customer)" = "None" ] \
    && [ "$(fixtures verification-by-email "$EMAIL" | json "d and d['id_appointments']")" = "$APPOINTMENT" ]; then
    ok "opening the link only shows the confirm button and changes nothing"
else
    fail "opening the link (HTTP $STATUS): appointment $(fixtures get "$APPOINTMENT"), verification $(fixtures verification-by-email "$EMAIL")"
fi

RESULT=$(confirm_link "$PHONE" "$TOKEN")
CUSTOMER=$(appointment_field "$APPOINTMENT" id_users_customer)
if [[ "${RESULT%% *}" == 30? ]] && [[ "${RESULT#* }" == *"booking_confirmation/of/$HASH" ]]; then
    ok "the confirm button redirects to the success page"
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

# 3. A second confirmation is invalid.
RESULT=$(confirm_link "$PHONE" "$TOKEN")
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
RESULT=$(confirm_link "$TMP/late-phone" "$LATE_TOKEN")
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

RESULT=$(confirm_link "$TMP/taken-phone" "$TAKEN_TOKEN")
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
RESULT=$(confirm_link "$TMP/taken-phone" "$TAKEN_TOKEN")
if [ "$(fixtures verification-by-email "$TAKEN_EMAIL")" = "null" ] && [ "${RESULT%% *}" = "200" ] \
    && grep -qF "$INVALID" "$TMP/page"; then
    ok "after 24 hours the cleanup deletes the restore data, the link is invalid"
else
    fail "after 24 hours: verification $(fixtures verification-by-email "$TAKEN_EMAIL"), link $RESULT"
fi

fixtures cleanup "${SLOT_TAKER:-}" "" >/dev/null

# 9. A Vormerkung deleted another way than by the expiry cleanup (staff, API, a deleted provider) is not restorable:
#    a late click neither books it nor changes anything.
DELETED_EMAIL="smoke-verify-deleted-$$-$RANDOM@example.com"
register "$TMP/deleted" "$SECOND" "$DELETED_EMAIL" >/dev/null
DELETED_APPOINTMENT=$(fixtures verification-by-email "$DELETED_EMAIL" | json "d and d['id_appointments']")
DELETED_TOKEN="smokedeleted$$$RANDOM"
fixtures verification-token "$DELETED_APPOINTMENT" "$DELETED_TOKEN" >/dev/null
fixtures cleanup "$DELETED_APPOINTMENT" "" >/dev/null
fixtures verification-token "$DELETED_EMAIL" "$DELETED_TOKEN" 11 >/dev/null
RESULT=$(confirm_link "$TMP/deleted-phone" "$DELETED_TOKEN")
if [ "${RESULT%% *}" = "200" ] && grep -qF "$INVALID" "$TMP/page" \
    && [ "$(fixtures verification-by-email "$DELETED_EMAIL" | json "d and d['id_appointments'] is None and d['restorable'] is False")" = "True" ] \
    && available_hours | json "'$SECOND_HOUR' in d" | grep -q True; then
    ok "a late click on a Vormerkung deleted another way than by expiry shows the invalid page and books nothing"
else
    fail "late click on a deleted Vormerkung: $RESULT, verification $(fixtures verification-by-email "$DELETED_EMAIL"), slots $(available_hours)"
fi

# 10. "Erneut senden": the Vormerkung comes from the browser session, the client sends no email or ID.
#     (This smoke test sends more than the 100 requests per two minutes of rate_limit(); its counter is reset.)
fixtures rate-limit-reset
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

RESULT=$(confirm_link "$TMP/resend-phone" "$OLD_TOKEN")
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

# 11. A booking with the email of a customer who already has an appointment at that time (two attendants, so the
#     slot itself is free) answers like any other; the check runs on confirmation, with a neutral page.
fixtures rate-limit-reset
OWNER_EMAIL=$(fixtures customer "$OWNER" | json "d['email']")
ORIGINAL_ATTENDANTS=$(fixtures service-attendants "$SERVICE" | tail -1)
fixtures service-attendants "$SERVICE" 2 >/dev/null
STATUS=$(register "$TMP/known" "$OWNER_START" "$OWNER_EMAIL")
KNOWN_APPOINTMENT=$(fixtures verification-by-email "$OWNER_EMAIL" | json "d and d['id_appointments']")
if [ "$STATUS" = "200" ] && json "d.get('verification_pending') is True" <"$TMP/body" | grep -q True \
    && [ -n "$KNOWN_APPOINTMENT" ] && [ "$KNOWN_APPOINTMENT" != "None" ]; then
    CREATED_APPOINTMENTS="$CREATED_APPOINTMENTS,$KNOWN_APPOINTMENT"
    ok "booking with the email of a customer booked at that time answers like any other booking (Vormerkung)"
else
    fail "booking with the email of a booked customer (HTTP $STATUS, $(cat "$TMP/body"))"
fi

KNOWN_TOKEN="smokeknown$$$RANDOM"
fixtures verification-token "$OWNER_EMAIL" "$KNOWN_TOKEN" >/dev/null
RESULT=$(confirm_link "$TMP/known-phone" "$KNOWN_TOKEN")
NOT_BOOKED=$(fixtures lang email_verification_not_booked_title | tail -1)
if [ "${RESULT%% *}" = "200" ] && grep -qF "$NOT_BOOKED" "$TMP/page" \
    && [ "$(fixtures get "$KNOWN_APPOINTMENT")" = "null" ] \
    && [ "$(fixtures verification-by-email "$OWNER_EMAIL")" = "null" ] \
    && [ "$(appointment_field "$OWNER_APPOINTMENT" id_users_customer)" = "$OWNER" ]; then
    ok "its confirmation shows a neutral page and drops the Vormerkung"
else
    fail "confirmation for a booked customer: $RESULT, Vormerkung $(fixtures get "$KNOWN_APPOINTMENT"), verification $(fixtures verification-by-email "$OWNER_EMAIL")"
fi
fixtures service-attendants "$SERVICE" "$ORIGINAL_ATTENDANTS" >/dev/null
ORIGINAL_ATTENDANTS=""

# 12. Reschedule with a changed email (spec 0018/05): the new time applies right away, the email waits for the link.
VICTIM_EMAIL=$(fixtures customer "$VICTIM" | json "d['email']")
CHANGED_EMAIL="smoke-verify-change-$$-$RANDOM@example.com"
OWNER_JAR="$TMP/owner-laptop"
OWNER_MAILS_BEFORE=$(mail_count "$OWNER_EMAIL")
CUSTOMER_NOTIFICATIONS=$(fixtures setting customer_notifications | tail -1)

STATUS=$(register "$OWNER_JAR" "$TARGET" "$CHANGED_EMAIL" "$OWNER_HASH")
CHANGE=$(fixtures verification-by-email "$CHANGED_EMAIL")
if [ "$STATUS" = "200" ] && json "d.get('appointment_hash') == '$OWNER_HASH' and 'verification_pending' not in d" <"$TMP/body" | grep -q True \
    && [ "$(appointment_field "$OWNER_APPOINTMENT" start_datetime)" = "$TARGET" ] \
    && [ "$(appointment_field "$OWNER_APPOINTMENT" id_users_customer)" = "$OWNER" ] \
    && [ "$(echo "$CHANGE" | json "d and d['purpose'] == 'email_change' and d['id_appointments'] == $OWNER_APPOINTMENT")" = "True" ]; then
    ok "reschedule with a changed email applies the new time right away and stores an email change"
else
    fail "reschedule with a changed email (HTTP $STATUS, $(cat "$TMP/body"), appointment $(fixtures get "$OWNER_APPOINTMENT"), verification $CHANGE)"
fi

if [ "$(contact_field "$OWNER_APPOINTMENT" email)" = "$OWNER_EMAIL" ] \
    && [ "$(fixtures customer "$OWNER" | json "d['email']")" = "$OWNER_EMAIL" ] \
    && [ "$(mail_count "$OWNER_EMAIL")" = "$((OWNER_MAILS_BEFORE + (CUSTOMER_NOTIFICATIONS == 1 ? 1 : 0)))" ] \
    && [ "$(mail_count "$CHANGED_EMAIL")" = "1" ]; then
    ok "until the click contact and customer keep the old email, the reschedule mail goes there, the new email gets only the link"
else
    fail "before the click: contact $(fixtures contact "$OWNER_APPOINTMENT"), customer $(fixtures customer "$OWNER"), mails old $(mail_count "$OWNER_EMAIL") (before $OWNER_MAILS_BEFORE), new $(mail_count "$CHANGED_EMAIL")"
fi

# Expiry changes nothing at the appointment; the expired link is invalid.
CHANGE_TOKEN="smokechange$$$RANDOM"
fixtures verification-token "$CHANGED_EMAIL" "$CHANGE_TOKEN" 11 >/dev/null
available_hours >/dev/null
RESULT=$(confirm_link "$TMP/owner-phone" "$CHANGE_TOKEN")
if [ "${RESULT%% *}" = "200" ] && grep -qF "$INVALID" "$TMP/page" \
    && [ "$(appointment_field "$OWNER_APPOINTMENT" start_datetime)" = "$TARGET" ] \
    && [ "$(appointment_field "$OWNER_APPOINTMENT" id_users_customer)" = "$OWNER" ] \
    && [ "$(contact_field "$OWNER_APPOINTMENT" email)" = "$OWNER_EMAIL" ] \
    && [ "$(fixtures customer "$OWNER" | json "d['email']")" = "$OWNER_EMAIL" ]; then
    ok "expired email change keeps the appointment and the old email"
else
    fail "expired email change: $RESULT, appointment $(fixtures get "$OWNER_APPOINTMENT"), contact $(fixtures contact "$OWNER_APPOINTMENT")"
fi

# A new reschedule with the changed email sends a new link; the click takes the email over.
STATUS=$(register "$OWNER_JAR" "$TARGET" "$CHANGED_EMAIL" "$OWNER_HASH")
fixtures verification-token "$CHANGED_EMAIL" "$CHANGE_TOKEN" >/dev/null
RESULT=$(confirm_link "$TMP/owner-phone" "$CHANGE_TOKEN")
if [ "$STATUS" = "200" ] && [[ "${RESULT%% *}" == 30? ]] && [[ "${RESULT#* }" == *"booking_confirmation/of/$OWNER_HASH" ]] \
    && [ "$(contact_field "$OWNER_APPOINTMENT" email)" = "$CHANGED_EMAIL" ] \
    && [ "$(fixtures customer "$OWNER" | json "d['email']")" = "$CHANGED_EMAIL" ] \
    && [ "$(appointment_field "$OWNER_APPOINTMENT" start_datetime)" = "$TARGET" ] \
    && [ "$(fixtures verification-by-email "$CHANGED_EMAIL")" = "null" ]; then
    ok "the email change link takes the new email into contact and customer record"
else
    fail "email change link (HTTP $STATUS): $RESULT, contact $(fixtures contact "$OWNER_APPOINTMENT"), customer $(fixtures customer "$OWNER"), verification $(fixtures verification-by-email "$CHANGED_EMAIL")"
fi

# A reschedule without email change needs no link.
STATUS=$(register "$TMP/owner-other" "$OWNER_START" "$CHANGED_EMAIL" "$OWNER_HASH")
if [ "$STATUS" = "200" ] && json "d.get('appointment_hash') == '$OWNER_HASH'" <"$TMP/body" | grep -q True \
    && [ "$(appointment_field "$OWNER_APPOINTMENT" start_datetime)" = "$OWNER_START" ] \
    && [ "$(fixtures verification-by-email "$CHANGED_EMAIL")" = "null" ] \
    && [ "$(mail_count "$CHANGED_EMAIL")" = "$((2 + (CUSTOMER_NOTIFICATIONS == 1 ? 1 : 0)))" ]; then
    ok "reschedule without email change needs no verification, its mail goes to the confirmed email"
else
    fail "reschedule without email change (HTTP $STATUS, $(cat "$TMP/body"), verification $(fixtures verification-by-email "$CHANGED_EMAIL"), mails $(mail_count "$CHANGED_EMAIL"))"
fi

# The email of another customer reaches the appointment contact only, both customer records stay unchanged.
register "$OWNER_JAR" "$OWNER_START" "$VICTIM_EMAIL" "$OWNER_HASH" >/dev/null
TAKEN_CHANGE_TOKEN="smoketakenchange$$$RANDOM"
fixtures verification-token "$OWNER_APPOINTMENT" "$TAKEN_CHANGE_TOKEN" >/dev/null
RESULT=$(confirm_link "$TMP/owner-phone" "$TAKEN_CHANGE_TOKEN")
if [[ "${RESULT%% *}" == 30? ]] && [ "$(contact_field "$OWNER_APPOINTMENT" email)" = "$VICTIM_EMAIL" ] \
    && [ "$(fixtures customer "$OWNER" | json "d['email']")" = "$CHANGED_EMAIL" ] \
    && [ "$(fixtures customer "$VICTIM" | json "d['email']")" = "$VICTIM_EMAIL" ] \
    && [ "$(appointment_field "$OWNER_APPOINTMENT" id_users_customer)" = "$OWNER" ]; then
    ok "email change to another customer's email changes only the appointment contact"
else
    fail "email change to a taken email: $RESULT, contact $(fixtures contact "$OWNER_APPOINTMENT"), owner $(fixtures customer "$OWNER"), appointment $(fixtures get "$OWNER_APPOINTMENT")"
fi

# 13. A new email that reached its hourly mail limit: the reschedule applies, the email stays the old one, no link.
LIMITED_EMAIL="smoke-verify-limited-$$-$RANDOM@example.com"
fixtures limit-fill "$LIMITED_EMAIL" >/dev/null
CONTACT_EMAIL=$(contact_field "$OWNER_APPOINTMENT" email)
STATUS=$(register "$OWNER_JAR" "$TARGET" "$LIMITED_EMAIL" "$OWNER_HASH")
if [ "$STATUS" = "200" ] && json "d.get('appointment_hash') == '$OWNER_HASH'" <"$TMP/body" | grep -q True \
    && [ "$(appointment_field "$OWNER_APPOINTMENT" start_datetime)" = "$TARGET" ] \
    && [ "$(contact_field "$OWNER_APPOINTMENT" email)" = "$CONTACT_EMAIL" ] \
    && [ "$(fixtures verification-by-email "$LIMITED_EMAIL")" = "null" ] \
    && [ "$(mail_count "$LIMITED_EMAIL")" = "0" ]; then
    ok "reschedule with a new email over its hourly limit applies, keeps the old email and sends no link"
else
    fail "reschedule with a limited new email (HTTP $STATUS, $(cat "$TMP/body")), appointment $(fixtures get "$OWNER_APPOINTMENT"), contact $(fixtures contact "$OWNER_APPOINTMENT"), mails $(mail_count "$LIMITED_EMAIL")"
fi

exit $FAIL
