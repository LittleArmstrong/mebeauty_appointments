# 05: Backend wording

**What to build:** Backend German texts use "Mitarbeiterin/Mitarbeiter" ("Mitarbeitende") for providers,
"Assistenz" for secretaries and "Dienstleistung" for services, and leftovers like "Sekretär Save" or
"Service speichern" are fixed; hints and messages reworded per the style guide.

**Blocked by:** 01

**Status:** needs-verification

- [x] Role terms unified (provider, secretary, service)
- [x] Leftover and half-translated strings fixed
- [x] Settings hints and messages reworded
- [x] Manual test cases in `docs/manual-tests.md`
- [x] `Rot vor Fix:` line recorded
- [x] `bash scripts/test.sh` green
- [ ] Visual check by the user (ends as `needs-verification`)

## Comments

- Implementer (subagent, worktree `agent-a97f740362d3faeba`, branch `agent/0025-05-backend`): reworded about 220
  German keys of the backend after checking where each shared key is used (views, `assets/js`, `Ics_file`, email
  templates, webhook action constants). No new keys, no English change (no English text was functionally wrong).
  - Role terms: `provider` "Anbieter" → "Mitarbeiterin/Mitarbeiter" (also the detail label in the emails, ICS file,
    calendar modals and popover, as tickets 02/03 left it), `providers` → "Mitarbeitende" (menu, page title, calendar
    filter, services and secretaries pages), `secretaries` "Bearbeiter" → "Assistenz", `admins` "Administrator" →
    "Admins", `customer` "Kunde" → "Kundin/Kunde" (ICS, popover); all saved/deleted messages, delete dialogs and
    webhook event labels follow ("Assistenz speichern", "Dienstleistung speichern", "Dienstleistung löschen").
  - Functionally wrong German texts fixed: `delete_provider` "Zeitraum löschen" → "Mitarbeiterin/Mitarbeiter
    löschen"; `start_date_before_end_error` said the opposite ("liegt vor") → "liegt nach"; `passwords_mismatch`
    "Passwort ungültig." → "Die Passwörter stimmen nicht überein."; `private` "Privatgelände" → "Privat"; `clear`
    "Deaktivieren" → "Leeren"; `attendants_number` "Begleiternummer" → "Anzahl Teilnehmende (gleichzeitige
    Buchungen)"; `not_working` "Sonn- und Feiertage" → "Arbeitsfrei"; `hour` "Uhr" → "Stunde" (timepicker);
    `to` "Zu" → "bis" (date range separator); `date_format_hint` "D - Datum" → "D = Tag"; `add_break` "Interval
    Hinzufügen" → "Pause hinzufügen"; `api_token_hint` "Setze" (informal) → "Legen Sie … fest"; weekday short forms
    "Son/Mon/Die…" → "So/Mo/Di…".
  - Keys left to this ticket: `backend_section` "Backend-Sektion" → "Verwaltung" (login heading, footer button,
    logout/update pages, backend tab title); `legal_notice` "Rechtlicher Hinweis" → "Rechtliche Hinweise" (footer
    link and settings section; `imprint` "Impressum" is separate); `any_provider` (booking settings switch) and the
    unused `display_any_provider` → "Option „Egal bei wem“ anzeigen", hint names „Egal bei wem“ as in the booking
    step.
  - New term choices, added to the style guide's term table: unavailability "Nichtverfügbarkeit" → "Abwesenheit"
    (calendar entry, dialogs, messages, webhook labels); backend → "Verwaltung". Settings page `business_logic`
    "Unternehmerische Logik" → "Geschäftsregeln".
  - Hints and messages: "erfolgreich gesichert" → "gespeichert", hints in short active "Sie" sentences (working plan,
    breaks, captcha, customer notifications, limit customer access, blocked periods, integrations, Google Calendar),
    "Zu Google Calendar hinzufügen" hint now names the button "Zum Google-Kalender hinzufügen" exactly.
  - Unchanged on purpose: keys settled by 02–04; `appointment_registered` (booking flow); `update` (unused);
    the other German CodeIgniter language files (none of the avoided terms in them). Grep of the German file
    for "Anbieter", "Dienstleister", "Sekretär", "Bearbeiter", "Service", "du/dich/dir/dein": none left (only
    "Drittanbietern" was rewritten away with `integrations_info`).
  - Manual test cases T-DE-29 … T-DE-35 (section 0025, "Backend"); T-DE-10 updated to "Cookie-Hinweis anzeigen".
    CHANGELOG entry of 0025 extended. No smoke asserts the changed texts.
- Rot vor Fix: new `GermanTranslationTermsTest` (no avoided style-guide term or informal address in
  `german/translations_lang.php`) failed before the rewording with 36 violations, e.g. "provider (Anbieter)",
  "secretaries (Bearbeiter)", "secretary_save (Sekretär)", "service_save (Service)", "slot_capacity_hint
  (Dienstleister)"; green afterwards. The rewording itself: entfällt – text-only change, checked visually.
- Validation: `bash scripts/test.sh` aborted at `docker compose up -d` (port 1025 held by the foreign container
  `platform-mailpit-1`), so the box above stays open. Run individually in the worktree: phpunit OK (238 tests),
  check:views, check:assets, check:format (npm), check:scratch OK. Smokes not run.

- Review fix (subagent, branch `agent/0025-review-fixes`): `enter_password_here` and `personal_information` were
  defined twice in German and English (identical texts); the earlier, dead definitions are removed, the later
  (effective) ones kept. `GermanTranslationTermsTest`: label `du/dich/dein` renamed to `du/dich/dir/dein` (the
  pattern also matches "dir"), the docblock now names exactly the checked terms and says the other "Avoid" entries
  are not checked there. Rot vor Fix: entfällt – test and data cleanup, no behaviour change.

**2026-10-28, orchestrator:** `bash scripts/test.sh` green in the main checkout on merge commit `74984699` (integration branch incl. review fixes): PHPUnit, all checks and all smokes OK. Open: visual check by the user (`docs/manual-tests.md`, section 0025).
