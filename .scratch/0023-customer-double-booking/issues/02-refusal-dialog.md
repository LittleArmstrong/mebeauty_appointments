# 02: Customer-facing refusal dialog and branded error title

**What to build:** Expected booking refusals (customer overlap, requested hour no longer available) are marked by
the server and shown on the booking page in the message dialog with the title "Termin nicht möglich" /
"Appointment not possible" and a plain text, without the "unexpected problem" heading; the wizard keeps all
entered data. The overlap text becomes "Sie haben zu dieser Zeit bereits einen Termin bei uns. Bitte wählen Sie
eine andere Uhrzeit." / "You already have an appointment with us at this time. Please choose another time.".
Unexpected errors keep the generic dialog, titled with the company name instead of "Easy!Appointments" (booking
page and backend).

**Blocked by:** 01

**Status:** needs-verification

- [x] Server marks expected refusals; smoke asserts the marker for the overlap and the unavailable-hour case
- [x] Booking page shows expected refusals with fitting title and plain text; entered data kept
- [x] New German and English wording; other languages unchanged
- [x] Generic error dialog titled with the company name (booking page and backend)
- [x] Manual test cases in `docs/manual-tests.md` (refusal DE/EN, unexpected error title)
- [x] `Rot vor Fix:` line recorded
- [ ] Visual check by the user (ends as `needs-verification`)
- [x] `bash scripts/test.sh` green

## Comments

**2026-10-17, implementer (worktree `.claude/worktrees/agent-a23fbde878e9a7657`, branch `agent/0023-02-refusal-dialog`):**

Server: fork library `Booking_refusal::respond()` answers an expected refusal with HTTP 409 and
`{"success": false, "booking_refused": true, "title": lang('appointment_not_possible'), "message": …}`.
`Booking::register()` calls it instead of the two `RuntimeException`s (requested hour unavailable, customer overlap).
The title comes from the server, so languages without a fork translation get the English fallback of `lang()`.

Client: fork module `assets/js/utils/booking_refusal.js` (`isRefusal()`, `show()`), loaded on the booking page.
`booking_http_client.js` calls `show()` in the existing `.fail()` (the wizard is not touched, entered data stays);
`app.js` skips marked refusals in the global error handler and titles the generic dialog with
`vars('company_name')` (escaped). `EA_Controller` exposes `company_name` as common script var (fallback
`DEFAULT_COMPANY_NAME`, also before installation). Wording DE/EN of `customer_is_already_booked` replaced, new key
`appointment_not_possible` in German and English.

Tests: `tests/smoke/lib.sh` gets `booking_refused` (marker set, title = `appointment_not_possible`);
`customer-overlap-smoke.sh` requires HTTP 409 and the marker for every refusal, `manage-link-smoke.sh` case 6
(target slot after the booking deadline → requested hour unavailable) as well.

Rot vor Fix: against the code of `agent/0023-customer-double-booking` (served from this worktree)
`customer-overlap-smoke.sh` failed with `FAIL partial overlap at the start (HTTP 500, {"success":false,"message":"Dieser
Kunde hat bereits einen Termin …"})` and the same for the other five refusal cases; `manage-link-smoke.sh` failed with
`FAIL target slot after booking deadline (HTTP 500, {"success":false,"message":"Der gewünschte Termin ist leider nicht
verfügbar. …"})`. Green after the change: both smokes exit 0.

Validation: `bash scripts/test.sh` in the worktree exit 3 (PHPUnit and all checks OK, smokes skipped);
`customer-overlap-smoke.sh`, `manage-link-smoke.sh`, `email-verification-smoke.sh` and
`appointment-contacts-smoke.sh` exit 0 against the worktree (nginx path + fixtures in the worktree directory, as in 01).

Open: the visual check of the dialogs by the user (`docs/manual-tests.md`, section 0023, T-DB-01 to T-DB-06).

**2026-10-18, review fix (worktree `.claude/worktrees/agent-aae2d64d96e2171b5`):**

The installation page loads `app.js` without `utils/string.js`, so `App.Utils.String.escapeHtml()` in the global
error handler threw and no error dialog appeared there. The title now comes HTML-escaped from the server: new
fork library `Error_dialog` (`title()`, pure `title_for()`, unit test `ErrorDialogTest`) decides company name vs.
`DEFAULT_COMPANY_NAME` (also for an empty name, and before the installation via `is_app_installed()` instead of a
second `table_exists('settings')` guard). `EA_Controller` only exposes it as script var `error_dialog_title`,
`app.js` only passes `vars('error_dialog_title')` to the dialog (no escaping call left). The previous script var
`company_name` is gone.

Expected booking refusals are answered with HTTP 409 (previously 500 via `RuntimeException`); the CHANGELOG entry
now says so.

Rot vor Fix: `ErrorDialogTest` – `Failed opening required '…/application/libraries/Error_dialog.php'` before the
library existed. The installation-page TypeError itself is visual (`docs/manual-tests.md`), not covered by a smoke.

Validation: see ticket 01, same review-fix run.
