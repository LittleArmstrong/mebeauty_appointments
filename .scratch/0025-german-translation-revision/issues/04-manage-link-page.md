# 04: Manage-Link page wording

**What to build:** The Manage-Link page speaks of "stornieren" and "umbuchen": the banner hint matches its button,
the button gets its own key ("Termin stornieren" / "Cancel appointment") instead of `delete`, the cancellation
reason prompt addresses the customer, and the deadline/locked messages and the "delete personal data" bar read
naturally. English hint and button fixed to match each other.

**Blocked by:** 01

**Status:** in-progress

- [x] Cancellation banner: hint and own button key match (German and English)
- [x] Cancellation reason prompt reworded
- [x] Deadline and locked messages reworded
- [x] Delete-personal-data bar reworded
- [x] Manual test cases in `docs/manual-tests.md`
- [x] `Rot vor Fix:` line recorded
- [ ] `bash scripts/test.sh` green
- [ ] Visual check by the user (ends as `needs-verification`)

## Comments

- Implementer (subagent, worktree `agent-a666b6cc1bd981afd`, branch `agent/0025-04-manage-link`): read every key of
  the manage mode against its view/code path (`booking_cancellation_frame.php`, the manage branch of
  `assets/js/pages/booking.js`, `booking_final_step.php`, `Booking`/`Booking_cancellation` message pages,
  `Booking_policy` lock texts, `pages/booking_cancellation.php`).
  - New fork keys (German and English), used only on the Manage-Link page:
    - `cancel_appointment_button` "Termin stornieren" / "Cancel appointment": the bar's button (replaces `delete`)
      and the confirming button of the cancellation dialog (replaces `confirm`).
    - `keep_appointment_button` "Termin behalten" / "Keep appointment": the dialog's dismiss button (replaces
      `close`, so it cannot be read as "cancel").
    - `cancellation_reason_prompt` "Bitte sagen Sie uns kurz, warum Sie den Termin stornieren. Ein Stichwort
      genügt." / "Please tell us briefly why you are cancelling the appointment. A few words are enough." (replaces
      `write_appointment_removal_reason`, which stays for the backend calendar's delete dialog).
    - `reschedule_appointment_button` "Umbuchung bestätigen" / "Update": the last step's button in manage mode
      (replaces `update`; English text unchanged). `update` itself is no longer used by the fork and keeps
      "Aktualisierung".
  - Reworded keys (old → new):
    - `cancel_appointment_hint` German "Bitte klicken Sie auf "Abbrechen" um den Termin … zu löschen." → "Sie können
      den Termin nicht wahrnehmen? Klicken Sie auf „Termin stornieren“."; English "Press the "Cancel" button …" →
      "Press the "Cancel appointment" button …" (functional fix: hint and button now match in both languages).
    - `appointment_cancelled` "Ihr Termin ist erfolgreich storniert worden." → "Ihr Termin wurde storniert."
    - `appointment_locked` "Änderungen unmöglich." → "Umbuchen und Stornieren nicht mehr möglich" (title of the
      manage lock pages only).
    - `appointment_locked_message` and `manage_locked_message_minutes` → "Ein Termin kann bis {$limit} Stunden vor
      Beginn umgebucht oder storniert werden." (the limit is "HH:MM"; the old minutes text lacked the unit).
    - `appointment_locked_message_deadline` and `manage_locked_message_deadline` → "Der Termin kann nicht mehr
      umgebucht oder storniert werden. Das war bis {$limit} möglich." (the old manage text said "sind … möglich"
      on a page shown after the deadline). All four lock texts are only reached with kind "manage".
    - `appointment_not_found` "Termin nicht gefunden." → "Termin nicht gefunden" (title; also the booking flow's
      error for a foreign hash/ID, fits there).
    - `appointment_does_not_exist_in_db` "… existiert nicht in der Systemdatenbank." → "Diesen Termin gibt es nicht
      mehr. Vielleicht wurde er bereits storniert oder der Link ist unvollständig."
    - Delete-personal-data bar (shared with the booking settings toggle, fits both): `delete_personal_information`
      "Persönliche Informationen löschen" → "Persönliche Daten löschen"; `delete_personal_information_hint` →
      "Löschen Sie alle Ihre Termine und persönlichen Daten bei uns."; `delete_personal_information_prompt` →
      "Möchten Sie Ihre persönlichen Daten und alle Ihre Termine wirklich löschen? Das lässt sich nicht rückgängig
      machen." Button stays `delete` "Löschen" (the guide allows "löschen" for personal data), dialog dismiss stays
      `cancel` "Abbrechen".
  - Decision for the user: the cancellation reason is **required** by client and server (`Booking_cancellation`
    aborts with 403 on an empty reason), so the prompt is gentle ("Ein Stichwort genügt.") but does not say
    "(optional)" as the style guide's example does. Making it truly optional is a behaviour change (client + server)
    and was left out.
  - Unchanged on purpose: `cancel_appointment_title` "Termin stornieren", `appointment_cancelled_title` "Termin
    storniert", `go_to_booking_page` "Weiter zur Buchungsseite" (shared with backend footer and verification pages).
  - Manual test cases T-DE-21 … T-DE-28 (section 0025, "Manage-Link page"); T-MA-05 updated to "Termin nicht
    gefunden". CHANGELOG entry of 0025 extended. `tests/smoke/manage-link-smoke.sh` reads expected texts via the
    language fixtures, so it needed no change.
- Rot vor Fix: `TranslationKeyParityTest` with the four new keys only in German failed with "Keys missing in
  english/translations_lang.php: cancel_appointment_button, keep_appointment_button, cancellation_reason_prompt,
  reschedule_appointment_button" (2 tests, 1 failure); green after adding them in English. The rewording itself:
  entfällt – text-only change, checked visually.
- Validation: `bash scripts/test.sh` aborted at `docker compose up -d` (port 1025 held by the foreign container
  `platform-mailpit-1`), so the box above stays open. Run individually in the worktree: phpunit OK (237 tests),
  check:views, check:assets, check:format (npm), check:scratch OK. Smokes not run.
