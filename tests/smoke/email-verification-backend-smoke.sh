#!/usr/bin/env bash
#
# HTTP smoke test of Vormerkungen in the backend calendar (spec 0018, ticket 04).
#
# Logs in as the seeded administrator, creates two customers with one appointment each (tests/smoke/fixtures.php),
# an email change verification on one of them and a Vormerkung (appointment without customer, booking
# verification) in a free slot of the same day, then calls the calendar endpoints the way the backend does:
# - the calendar and the table view load with the Vormerkung in range, mark it "unconfirmed" and show the name of
#   its appointment contact; an appointment with a pending email change stays confirmed;
# - the reschedule page of the Vormerkung opens with its contact;
# - staff cannot save (confirm or move) a Vormerkung;
# - deleting the Vormerkung also removes its verification (no later restore by the link).
# All test data is removed on exit.
#
# Requires the docker stack to be up (php-fpm, nginx, mysql), a provider with a service and the administrator
# from `console install` (override with SMOKE_ADMIN_USER / SMOKE_ADMIN_PASSWORD).

set -uo pipefail

. "$(dirname "$0")/lib.sh"

CREATED_APPOINTMENTS=""
CREATED_CUSTOMERS=""

cleanup() {
    fixtures cleanup "${CREATED_APPOINTMENTS#,}" "${CREATED_CUSTOMERS#,}" >/dev/null
    fixtures purge >/dev/null
    smoke_cleanup
}
trap cleanup EXIT

backend_login || { fail "backend login as administrator"; exit $FAIL; }

SETUP=$(fixtures setup | tail -1)
[ -n "$SETUP" ] || { fail "setup test data"; exit $FAIL; }

PROVIDER=$(echo "$SETUP" | json "d['provider_id']")
SERVICE=$(echo "$SETUP" | json "d['service_id']")
DATE=$(echo "$SETUP" | json "d['date']")
TARGET_HOUR=$(echo "$SETUP" | json "d['target_hour']")
VICTIM_APPOINTMENT=$(echo "$SETUP" | json "d['victim']['id']")
OWNER_APPOINTMENT=$(echo "$SETUP" | json "d['owner']['id']")
CREATED_APPOINTMENTS=",$VICTIM_APPOINTMENT,$OWNER_APPOINTMENT"
CREATED_CUSTOMERS=",$(echo "$SETUP" | json "d['victim']['customer_id']"),$(echo "$SETUP" | json "d['owner']['customer_id']")"

EMAIL="smoke-vormerkung-$(date +%s)$RANDOM@example.com"
VORMERKUNG_JSON=$(fixtures vormerkung "$PROVIDER" "$SERVICE" "$DATE" "$TARGET_HOUR:00" "$EMAIL" | tail -1)
VORMERKUNG=$(echo "$VORMERKUNG_JSON" | json "d['id']")
VORMERKUNG_HASH=$(echo "$VORMERKUNG_JSON" | json "d['hash']")
[ -n "$VORMERKUNG" ] || { fail "create Vormerkung"; exit $FAIL; }
CREATED_APPOINTMENTS="$CREATED_APPOINTMENTS,$VORMERKUNG"

fixtures email-change-verification "$OWNER_APPOINTMENT" "smoke-new-$RANDOM@example.com" >/dev/null

# Prints "<is_unconfirmed of the Vormerkung> <its customer first name> <is_unconfirmed of the owner appointment>"
# from a calendar response (file).
marking() {
    python3 -c "
import json
appointments = {str(a['id']): a for a in json.load(open('$1'))['appointments']}
v = appointments.get('$VORMERKUNG') or {}
o = appointments.get('$OWNER_APPOINTMENT') or {}
print(v.get('is_unconfirmed'), (v.get('customer') or {}).get('first_name'), o.get('is_unconfirmed'))" 2>/dev/null
}

# 1. Calendar (provider filter) with the Vormerkung in range.
backend_post calendar/get_calendar_appointments --data-urlencode "record_id=$PROVIDER" \
    --data-urlencode "filter_type=provider" --data-urlencode "start_date=$DATE" \
    --data-urlencode "end_date=$DATE" >"$TMP/calendar"
SHOWN=$(marking "$TMP/calendar")
if [ "$SHOWN" = "True Vera False" ]; then
    ok "calendar loads with a Vormerkung, marks it unconfirmed with its contact name, email change stays confirmed"
else
    fail "calendar with Vormerkung: '$SHOWN' ($(head -c 300 "$TMP/calendar"))"
fi

# 2. Table view.
backend_post calendar/get_calendar_appointments_for_table_view --data-urlencode "start_date=$DATE" \
    --data-urlencode "end_date=$DATE" >"$TMP/table"
SHOWN=$(marking "$TMP/table")
if [ "$SHOWN" = "True Vera False" ]; then
    ok "table view loads with a Vormerkung and marks it unconfirmed"
else
    fail "table view with Vormerkung: '$SHOWN' ($(head -c 300 "$TMP/table"))"
fi

# 3. Reschedule page of the Vormerkung (dialog prefilled with the contact).
STATUS=$(curl -s -o "$TMP/page" -w '%{http_code}' -b "$COOKIES" "$BASE/index.php/calendar/reschedule/$VORMERKUNG_HASH")
if [ "$STATUS" = "200" ] && grep -q '"first_name":"Vera","last_name":"Vormerkung"' "$TMP/page" \
    && grep -q '"is_unconfirmed":true' "$TMP/page"; then
    ok "reschedule page of a Vormerkung opens with its contact, marked unconfirmed"
else
    fail "reschedule page of the Vormerkung (HTTP $STATUS)"
fi

# 4. Saving (moving, or confirming with customer data) a Vormerkung is refused.
START=$(fixtures get "$VORMERKUNG" | json "d['start_datetime']")
END=$(fixtures get "$VORMERKUNG" | json "d['end_datetime']")
backend_post calendar/save_appointment \
    --data-urlencode "appointment_data[id]=$VORMERKUNG" \
    --data-urlencode "appointment_data[id_users_provider]=$PROVIDER" \
    --data-urlencode "appointment_data[id_services]=$SERVICE" \
    --data-urlencode "appointment_data[start_datetime]=$START" \
    --data-urlencode "appointment_data[end_datetime]=$END" \
    --data-urlencode "appointment_data[is_unavailability]=0" \
    --data-urlencode "customer_data[first_name]=Vera" \
    --data-urlencode "customer_data[last_name]=Vormerkung" \
    --data-urlencode "customer_data[email]=$EMAIL" \
    --data-urlencode "customer_data[timezone]=UTC" \
    --data-urlencode "customer_data[language]=english" \
    --data-urlencode "notify_users=false" --data-urlencode "force_save=true" >"$TMP/save"
# The message of appointment_unconfirmed_not_editable in the backend language (English or German).
if grep -q '"success":false' "$TMP/save" && grep -qE 'unconfirmed|unbest' "$TMP/save" \
    && [ "$(fixtures get "$VORMERKUNG" | json "d['id_users_customer']")" = "None" ]; then
    ok "staff cannot confirm a Vormerkung by saving it"
else
    fail "saving a Vormerkung: $(cat "$TMP/save")"
fi

# 5. Deleting the Vormerkung removes it with its verification.
backend_post calendar/delete_appointment --data-urlencode "appointment_id=$VORMERKUNG" \
    --data-urlencode "notify_users=true" >"$TMP/delete"
if grep -q '"success":true' "$TMP/delete" \
    && [ "$(fixtures get "$VORMERKUNG")" = "null" ] \
    && [ "$(fixtures verification-by-email "$EMAIL")" = "null" ]; then
    ok "deleting a Vormerkung also deletes its verification"
else
    fail "delete Vormerkung: $(cat "$TMP/delete"), verification $(fixtures verification-by-email "$EMAIL")"
fi

exit $FAIL
