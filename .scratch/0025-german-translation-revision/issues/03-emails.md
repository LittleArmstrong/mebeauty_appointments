# 03: Email wording

**What to build:** German texts of the customer and staff emails (booking saved/changed, cancelled, email
verification) reworded per the style guide; email templates declare the rendering language instead of
`<html lang="en">`.

**Blocked by:** 01

**Status:** needs-verification

- [x] Customer email texts reworded (German)
- [x] Staff email texts reworded (German)
- [x] `lang` attribute follows the rendering language
- [x] Manual test cases in `docs/manual-tests.md`
- [x] `Rot vor Fix:` line recorded
- [x] `bash scripts/test.sh` green
- [ ] Visual check by the user (ends as `needs-verification`)

## Comments

- Implementer (subagent, worktree `agent-a3a595f1a0e40a56d`, branch `agent/0025-03-emails`): read the code paths
  in `Notifications` (customer vs provider/admin/secretary subject and text, manage mode) and `Email_messages`
  (subjects of cancellation, password, reset and verification mails) and every key printed by
  `application/views/emails/*.php` and by `Email_verification_pages`.
  - `lang` attribute: new fork helper `language_code()` in `application/helpers/locale_helper.php` (autoloaded) maps
    the current rendering language `config('language')`, which `Notifications` switches to the recipient's
    language, to its code from `config('language_codes')`, "en" for unknown. All five email templates declare
    `<html lang="<?= e(language_code()) ?>">`. `config('language_code')` could not be used: it stays at the
    request's language while a mail is rendered for another recipient.
  - New fork key (German and English): `appointment_changed_customer_subject` "Ihr Termin wurde geändert" / "Your
    appointment has been changed", the customer's subject in manage mode (`Notifications::notify_appointment_saved`);
    staff keep `appointment_details_changed`, now neutral "Termin geändert".
  - Reworded German keys (old → new):
    - `appointment_booked` "Ihr Termin ist erfolgreich gebucht worden." → "Ihr Termin ist gebucht"
    - `thank_you_for_appointment` "Vielen Dank für die Terminauswahl mit uns. …" → "Vielen Dank für Ihre Buchung.
      Unten finden Sie alle Angaben zu Ihrem Termin. Über „Termin verwalten“ können Sie ihn umbuchen oder
      stornieren." (names the button)
    - `appointment_added_to_your_plan` (staff, also admins/secretaries) "Ein neuer Termin ist zu Ihrer Planung
      hinzugefügt worden." → "Neuer Termin gebucht"
    - `appointment_link_description` (staff) → "Über „Termin verwalten“ öffnen Sie den Termin im Kalender."
    - `appointment_details_changed` "Die Termindetails haben sich geändert." → "Termin geändert"
    - `appointment_removed_from_schedule` (customer and staff) "… erfolgreich von der Terminplanung des
      Unternehmens storniert." → "Der folgende Termin wurde storniert."
    - `new_account_password` "Neues Kontopasswort" → "Ihr neues Passwort"; `new_password_is` → "Ihr neues Passwort
      lautet $password. … unter „Konto“ ändern."
    - `password_reset_request` "Anfrage zur Passwortzurücksetzung" → "Passwort zurücksetzen";
      `password_reset_email_message` names the button "Passwort zurücksetzen"; `password_reset_link_expires` → "Der
      Link ist eine Stunde lang gültig."; `password_reset_ignore_message` → "… ignorieren Sie diese E-Mail einfach.
      Ihr bisheriges Passwort bleibt gültig."
    - Verification mail: `email_verification_message` → "Nur noch ein Schritt: Bestätigen Sie Ihre E-Mail-Adresse,
      dann ist Ihr Termin gebucht."; `email_verification_link_expires` "Der Termin ist 10 Minuten für Sie
      reserviert. …" → "Wir halten den Termin 10 Minuten für Sie frei. …"; `…_ignore_message`,
      `email_verification_change_message`, `…_change_link_expires`, `…_change_ignore_message` smoothed.
    - Verification landing pages: `email_verification_confirm_text` / `…_change_confirm_text` "Klicken Sie auf die
      Schaltfläche, …" → name the button ("Klicken Sie auf „Termin bestätigen“, …" / "„E-Mail-Adresse
      bestätigen“"); `email_verification_expired_title` "Leider abgelaufen" → "Link abgelaufen";
      `…_expired_text` and `…_not_booked_text` say "Uhrzeit" instead of "Termin"/"Zeit".
  - Unchanged on purpose: `appointment_cancelled_title` "Termin storniert" (also the cancellation page, ticket 04),
    `appointment_details_title`/`customer_details_title` (shared with the backend modal, neutral for both
    recipients), `manage_appointment`, the verification subjects and buttons, `email_verification_invalid_*`,
    `email_verification_pending_*` (booking flow, ticket 02), `go_to_booking_page`. The detail label `provider`
    still reads "Anbieter" in the mails: it is the shared backend key of ticket 05, the mails follow its rewording.
    The hardcoded footer "Powered by Easy!Appointments" stays (upstream attribution, not a translation). No English
    text was functionally wrong, so only the new key was added in English.
  - Manual test cases T-DE-13 … T-DE-20 (section 0025, "Emails"); T-EB-10 updated to "Link abgelaufen". CHANGELOG
    entry of 0025 extended.
- Rot vor Fix: `LanguageCodeTest` before the helper and template change: "Call to undefined function
  language_code()" (3 errors) and "account_recovery_email.php must declare the rendering language" (1 failure); green
  afterwards (4 tests). `TranslationKeyParityTest` with `appointment_changed_customer_subject` only in German: "Keys
  missing in english/translations_lang.php: appointment_changed_customer_subject"; green after adding it in English.
  The rewording itself: entfällt – text-only change, checked visually.
- Validation: `bash scripts/test.sh` aborted at `docker compose up -d` (port 1025 held by the foreign container
  `platform-mailpit-1`; the project's own Mailpit therefore does not start either), so the box above stays open. Run
  individually in the worktree: phpunit OK (237 tests), check:views, check:assets, check:format, check:scratch OK.
  Smokes not run.

- Review fix (subagent, branch `agent/0025-review-fixes`):
  `LanguageCodeTest::test_email_templates_declare_the_rendering_language` compared a literal source string; it now
  uses a regex that allows other attributes, spacing and either quote but still requires `e(language_code())`
  echoed in the `lang` attribute of the `<html>` tag. Rot vor Fix: entfällt – test robustness only.

**2026-10-28, orchestrator:** `bash scripts/test.sh` green in the main checkout on merge commit `74984699` (integration branch incl. review fixes): PHPUnit, all checks and all smokes OK. Open: visual check by the user (`docs/manual-tests.md`, section 0025).
