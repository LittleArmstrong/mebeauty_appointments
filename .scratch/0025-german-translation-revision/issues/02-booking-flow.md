# 02: Booking flow wording

**What to build:** All German texts of the booking wizard (service/person, time, details, confirmation, booking
messages and refusal dialogs) and the theme's `site_*` texts rewritten per the style guide. The person step asks
"Bei wem möchten Sie buchen?" with "Egal bei wem"; the WhatsApp button uses "Sie". Own keys where a shared key
cannot be worded right; English fixed only where functionally wrong.

**Blocked by:** 01

**Status:** in-progress

- [x] Wizard steps and booking messages reworded (German)
- [x] Person step with question wording, "Egal bei wem"
- [x] `site_*` texts in "Sie"
- [x] Own keys where needed, in German and English
- [x] Manual test cases in `docs/manual-tests.md`
- [x] `Rot vor Fix:` line recorded
- [ ] `bash scripts/test.sh` green
- [ ] Visual check by the user (ends as `needs-verification`)

## Comments

- Implementer (subagent, worktree `agent-a280ce426621b86a2`, branch `agent/0025-02-booking-flow`): every key shown
  in the booking wizard (`booking_*` components, `assets/js/pages/booking.js`, `assets/js/utils/altcha.js`, booking
  HTTP client, `Booking`/`Booking_refusal` messages) and the theme `site_*` keys read against the view that uses
  it. New fork keys (German and English), used only in the booking flow:
  `booking_provider_question` "Bei wem möchten Sie buchen?" / "Who would you like to book with?" (label of the
  provider select in `booking_type_step.php`, replaces `provider`) and `booking_any_provider` "Egal bei wem" /
  "Any Provider" (option in `booking.js`, replaces `any_provider`, which stays the backend switch label for
  ticket 05). Reworded German keys (old → new):
  - `service_and_provider` "Dienstleistung & Anbieter" → "Dienstleistung auswählen"
  - `appointment_date_and_time` "Datum & Uhrzeit des Termins" → "Datum und Uhrzeit auswählen"
  - `customer_information` "Kundeninformationen" → "Ihre Angaben"
  - `appointment_confirmation` "Bitte bestätigen Sie die Terminauswahl" → "Termin bestätigen"
  - `confirm` "Bestätigung" → "Bestätigen" (button; shared with the calendar sync dialog, fits both)
  - `please_select` "Bitte Wählen" → "Bitte auswählen"; `contact_info` "Kontakt-Infos" → "Ihre Kontaktdaten"
  - `no_available_service_providers(_message)` "Keine verfügbaren Dienste" / "… Dienste oder Anbieter …" →
    "Online-Buchung derzeit nicht möglich" / "Zurzeit können keine Termine online gebucht werden. …"
  - `appointment_hour_missing` → "Bitte wählen Sie eine Uhrzeit, bevor Sie fortfahren."
  - `no_available_hours` → "An diesem Tag sind leider keine Termine frei. Bitte wählen Sie ein anderes Datum."
  - `requested_hour_is_unavailable` "Der gewünschte Termin ist leider nicht verfügbar. …" → "Diese Uhrzeit ist
    leider nicht mehr frei. Bitte wählen Sie eine andere Uhrzeit."
  - `fields_are_required` → "Felder mit * sind Pflichtfelder." (shared with the backend, fits both)
  - `booking_is_disabled` "Buchung ist deaktiviert!" → "Online-Buchung ist derzeit nicht möglich"
  - `page_title` "Vereinbaren Sie einen Termin mit" → "Termin buchen bei"
  - ALTCHA/CAPTCHA (shared with login and password recovery): `verify` "Verifizieren" → "Ich bin kein Roboter"
    (avoids a second "Bestätigen" button on step 4), `click_to_verify`, `verifying`, `verified`,
    `verification_complete`, `verification_failed`, `altcha_verification_failed` → "Sicherheitsprüfung …" wording;
    `captcha_is_wrong` → "Der eingegebene Code ist falsch. Bitte versuchen Sie es erneut."
  - `website_using_cookies_to_ensure_best_experience` → "Diese Website verwendet Cookies, damit Sie sie bestmöglich
    nutzen können."
  - `site_whatsapp_chat` "Chatte mit uns auf WhatsApp" → "Schreiben Sie uns auf WhatsApp"

  Unchanged on purpose: labels of the details step (already natural), verification notice and refusal title
  (already "Sie"), the other `site_*` keys (no address form, they mirror the mebeauty-Site navigation and footer),
  `update`, `appointment_not_found`/`appointment_does_not_exist_in_db` and the cancellation frame (manage mode,
  ticket 04), `backend_section`/`legal_notice` (backend wording, ticket 05), the email verification landing pages
  (ticket 03/04). No English text was functionally wrong in the booking wizard, so none changed. Manual test
  cases T-DE-01 … T-DE-12 (section 0025); T-KA-01, T-DB-01 and T-DB-04 updated to the new texts. CHANGELOG entry
  under 0.1.0 "Changed".
- Rot vor Fix: `TranslationKeyParityTest` with the two new keys only in German failed with "Keys missing in
  english/translations_lang.php: booking_provider_question, booking_any_provider" (2 tests, 1 failure); green after
  adding them in English. The rewording itself: entfällt – text-only change, checked visually.
- Validation: `bash scripts/test.sh` aborted at `docker compose up -d` (port 1025 held by the foreign container
  `platform-mailpit-1`), so the box above stays open. Run individually in the worktree: phpunit OK (233 tests),
  check:views, check:assets, check:format, check:scratch OK. Smokes not run (text-only change; no smoke asserts the
  changed texts).

- Review fix (subagent, branch `agent/0025-review-fixes`): `service_and_provider` read "Dienstleistung auswählen",
  identical to `select_service`, although step 1 (`booking_type_step.php` heading, `booking_steps.php` tooltip)
  also chooses the person. Now "Dienstleistung und Person auswählen" (no job title, parallel to "Datum und Uhrzeit
  auswählen"). English unchanged. T-DE-01 and T-DE-02 updated.
