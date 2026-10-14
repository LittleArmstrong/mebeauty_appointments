#!/usr/bin/env bash
#
# HTTP smoke test of the appointment contacts in the backend calendar (spec 0017, ticket 05).
#
# Logs in as the seeded administrator, creates two customers with one future appointment each
# (tests/smoke/fixtures.php; the appointments have no contact yet, like older appointments) and calls
# calendar/save_appointment and the calendar endpoints the way the appointment dialog does:
# - editing a contact field changes the appointment contact and the customer record;
# - editing one field leaves the other record fields as stored, even when the record changed meanwhile;
# - moving an appointment (no customer data) leaves the contact unchanged;
# - a new appointment stores the dialog input as its contact;
# - the calendar and the reschedule page show the contact, older appointments the record.
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
    smoke_cleanup
}
trap cleanup EXIT

backend_login || { fail "backend login as administrator"; exit $FAIL; }

SETUP=$(fixtures setup | tail -1)
[ -n "$SETUP" ] || { fail "setup test data"; exit $FAIL; }

PROVIDER=$(echo "$SETUP" | json "d['provider_id']")
SERVICE=$(echo "$SETUP" | json "d['service_id']")
DATE=$(echo "$SETUP" | json "d['date']")
TARGET="$DATE $(echo "$SETUP" | json "d['target_hour']"):00"
ANNA_APPOINTMENT=$(echo "$SETUP" | json "d['victim']['id']")
ANNA_HASH=$(echo "$SETUP" | json "d['victim']['hash']")
ANNA=$(echo "$SETUP" | json "d['victim']['customer_id']")
BERND_APPOINTMENT=$(echo "$SETUP" | json "d['owner']['id']")
BERND=$(echo "$SETUP" | json "d['owner']['customer_id']")
CREATED_APPOINTMENTS=",$ANNA_APPOINTMENT,$BERND_APPOINTMENT"
CREATED_CUSTOMERS=",$ANNA,$BERND"

appointment_field() { fixtures get "$1" | json "d and d['$2']"; }
customer_field() { fixtures customer "$1" | json "d['$2']"; }
contact_field() { fixtures contact "$1" | json "d and d['$2']"; }

START=$(appointment_field "$ANNA_APPOINTMENT" start_datetime)
END=$(appointment_field "$ANNA_APPOINTMENT" end_datetime)
ANNA_EMAIL=$(customer_field "$ANNA" email)
BERND_EMAIL=$(customer_field "$BERND" email)

# Save like the appointment dialog. Arguments: appointment id ("" = new), start, end, customer id,
# then customer fields as name=value pairs (none = moving only, without customer data).
save_appointment() {
    local id=$1 start=$2 end=$3 customer=$4
    shift 4
    local args=(
        --data-urlencode "appointment_data[id_users_provider]=$PROVIDER"
        --data-urlencode "appointment_data[id_services]=$SERVICE"
        --data-urlencode "appointment_data[start_datetime]=$start"
        --data-urlencode "appointment_data[end_datetime]=$end"
        --data-urlencode "appointment_data[is_unavailability]=0"
        --data-urlencode "notify_users=false"
        --data-urlencode "force_save=true"
    )
    [ -n "$id" ] && args+=(--data-urlencode "appointment_data[id]=$id")
    if [ $# -gt 0 ]; then
        args+=(
            --data-urlencode "appointment_data[id_users_customer]=$customer"
            --data-urlencode "customer_data[id]=$customer"
            --data-urlencode "customer_data[timezone]=UTC"
            --data-urlencode "customer_data[language]=english"
        )
        local field
        for field in "$@"; do
            args+=(--data-urlencode "customer_data[${field%%=*}]=${field#*=}")
        done
    else
        args+=(--data-urlencode "appointment_data[id_users_customer]=$customer")
    fi

    backend_post calendar/save_appointment "${args[@]}" >"$TMP/body"
    grep -q '"success":true' "$TMP/body"
}

# 1. Dialog edit of an older appointment: the phone changes in the contact and in the record.
if save_appointment "$ANNA_APPOINTMENT" "$START" "$END" "$ANNA" first_name=Smoke last_name=Victim \
    "email=$ANNA_EMAIL" phone_number=0999 "address=Weg 5" \
    && [ "$(contact_field "$ANNA_APPOINTMENT" phone_number)" = "0999" ] \
    && [ "$(contact_field "$ANNA_APPOINTMENT" first_name)" = "Smoke" ] \
    && [ "$(customer_field "$ANNA" phone_number)" = "0999" ] \
    && [ "$(customer_field "$ANNA" address)" = "Weg 5" ]; then
    ok "dialog edit changes the contact field in the appointment contact and the record"
else
    fail "dialog edit of phone ($(cat "$TMP/body"), contact $(fixtures contact "$ANNA_APPOINTMENT"))"
fi

# 2. The record changes meanwhile; the dialog only edits the last name.
fixtures customer "$ANNA" first_name Anders >/dev/null
if save_appointment "$ANNA_APPOINTMENT" "$START" "$END" "$ANNA" first_name=Smoke last_name=Opfer \
    "email=$ANNA_EMAIL" phone_number=0999 \
    && [ "$(contact_field "$ANNA_APPOINTMENT" last_name)" = "Opfer" ] \
    && [ "$(contact_field "$ANNA_APPOINTMENT" first_name)" = "Smoke" ] \
    && [ "$(customer_field "$ANNA" last_name)" = "Opfer" ] \
    && [ "$(customer_field "$ANNA" first_name)" = "Anders" ]; then
    ok "only the edited field reaches the record, unchanged fields keep the record value"
else
    fail "dialog edit of last name ($(cat "$TMP/body"), record $(fixtures customer "$ANNA"))"
fi

# 3. Moving the appointment (no customer data).
CONTACT_BEFORE=$(fixtures contact "$ANNA_APPOINTMENT")
MOVED_END=$(python3 -c "from datetime import datetime as t; f='%Y-%m-%d %H:%M:%S'; print((t.strptime('$TARGET', f) + (t.strptime('$END', f) - t.strptime('$START', f))).strftime(f))")
if save_appointment "$ANNA_APPOINTMENT" "$TARGET" "$MOVED_END" "$ANNA" \
    && [ "$(appointment_field "$ANNA_APPOINTMENT" start_datetime)" = "$TARGET" ] \
    && [ "$(fixtures contact "$ANNA_APPOINTMENT")" = "$CONTACT_BEFORE" ]; then
    ok "moving an appointment leaves its contact unchanged"
else
    fail "moving ($(cat "$TMP/body"), contact $(fixtures contact "$ANNA_APPOINTMENT"))"
fi

# 4. New appointment for an existing customer (phone booking), in Anna's freed original slot.
if save_appointment "" "$START" "$END" "$BERND" first_name=Telefon last_name=Kundin "email=$BERND_EMAIL" \
    phone_number=0123; then
    NEW_APPOINTMENT=$(fixtures get-by-customer "$BERND" "$START" | json "d and d['id']")
    CREATED_APPOINTMENTS="$CREATED_APPOINTMENTS,$NEW_APPOINTMENT"
    if [ "$(contact_field "$NEW_APPOINTMENT" first_name)" = "Telefon" ] \
        && [ "$(contact_field "$NEW_APPOINTMENT" phone_number)" = "0123" ]; then
        ok "new appointment stores the dialog input as its contact"
    else
        fail "contact of new appointment $NEW_APPOINTMENT: $(fixtures contact "$NEW_APPOINTMENT")"
    fi
else
    fail "new appointment ($(cat "$TMP/body"))"
fi

# 5. Calendar and reschedule page show the contact (not the record, renamed here); Bernd's older appointment
#    shows his record.
fixtures customer "$ANNA" first_name Umbenannt >/dev/null
backend_post calendar/get_calendar_appointments --data-urlencode "record_id=$PROVIDER" \
    --data-urlencode "filter_type=provider" --data-urlencode "start_date=$DATE" \
    --data-urlencode "end_date=$DATE" >"$TMP/calendar"
SHOWN=$(python3 -c "
import json
shown = {str(a['id']): a['customer']['first_name'] for a in json.load(open('$TMP/calendar'))['appointments']}
print(shown.get('$ANNA_APPOINTMENT'), shown.get('$BERND_APPOINTMENT'))" 2>/dev/null)
if [ "$SHOWN" = "Smoke $(customer_field "$BERND" first_name)" ]; then
    ok "calendar shows the appointment contact, older appointments the record"
else
    fail "calendar customer names: $SHOWN"
fi

curl -s -b "$COOKIES" "$BASE/index.php/calendar/reschedule/$ANNA_HASH" >"$TMP/page"
if grep -q '"first_name":"Smoke","last_name":"Opfer"' "$TMP/page"; then
    ok "reschedule page prefills the dialog with the appointment contact"
else
    fail "reschedule page does not contain the appointment contact"
fi

exit $FAIL
