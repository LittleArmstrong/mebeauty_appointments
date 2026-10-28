# Manual Tests

Checklist for the manual run before each release. It covers what has to be seen in the browser, in mails or in the
calendar. Limits and rules already checked by PHPUnit or the smoke tests (`bash scripts/test.sh`) are only listed as
"Automated".

UI labels are quoted as they appear in the German UI.

**Maintenance:** Every spec with visible behaviour adds its test cases here (one test case per observable
acceptance criterion, with a ticket reference). Tick the boxes only in your local copy; the empty template is what
gets committed.

**Section headings:** A section is headed by the number of the spec that introduced it, then its title and the
tickets it verifies, e.g. `## 0020 Wizard step scroll — 0020/01`. Sections are ordered by that number; a new spec
appends its section at the end. No running numbers: two specs built in parallel never claim the same heading.
Preparation and basic run keep their names without a number.

**Feedback:** Report failures with the test case ID, e.g. "T-EB-03 fails: …". Passed sections verify the tickets
named in their heading.

Duration: about 1–2 hours for a complete run.

## Preparation

Local Docker stack (`docs/docker.md`). Test cases marked **[Test server]** need the Google sync and run on
`booking-test.mebeauty-koeln.de` with the Google account connected there.

- [ ] **T-0-01** Start the stack, validation is green:

  ```bash
  bash scripts/test.sh
  ```

- [ ] **T-0-02** Schema up to date: if the dev DB predates the latest state of migration `070`, set
  `ea_migrations.version` to `69` in phpMyAdmin (<http://localhost:8080>, `root`/`secret`), then:

  ```bash
  docker compose exec -T php-fpm php index.php console migrate
  ```

- [ ] **T-0-03** Test data in the backend (<http://localhost/index.php/calendar>), unless already present:
  - two providers **A** and **B**, working plan Mon–Fri 09:00–18:00 with a break 12:00–13:00
  - services **S60** (60 min) and **S30** (30 min), both assigned to A and B
- [ ] **T-0-04** Mailpit open: <http://localhost:8025>. All mails sent by the app end up there.

Initial settings for all sections unless a test case says otherwise:
theme `mebeauty`, deadline mode "Minuten-Vorlaufzeit" with 0, buffer time 0, reschedule/cancellation deadline
"Buchungsfrist übernehmen", email verification off, footer on. Reset after each section.

Time-dependent tests: instead of waiting, set deadline times a few minutes into the future or reset the expiry time
in the database (described in the respective test case).

## Basic run (upstream core functionality)

- [ ] **T-B-01** Booking flow (<http://localhost>): book S60 with A. The success page appears, customer and salon
  mail in Mailpit, appointment in the backend calendar.
- [ ] **T-B-02** In the backend calendar, create an appointment for an existing customer, move it and delete it.
  Each notification mail is written in its recipient's language (user profile setting): e.g. the seeded customer
  James has English and gets English mails, while admin and provider with German get German ones.

## 0001 Theme, branding, site chrome — 0001, 0004, 0005, 0014

- [ ] **T-TH-01** Theme `mebeauty` (Settings → General settings): the booking flow shows a black page, navbar with
  logo and four links to the mebeauty site, intro "Buchung" with hero image, Wizard-Card, website footer, WhatsApp
  button.
- [ ] **T-TH-02** Phone width (DevTools, 375 px): the hamburger menu opens and closes the navigation; nothing scrolls
  horizontally.
- [ ] **T-TH-03** Footer links to imprint and privacy policy lead to the URLs stored in the legal settings (without
  an entry: pages of the mebeauty site).
- [ ] **T-TH-04** Theme `default`: booking flow in the upstream layout, **no** MeBeauty chrome (navbar, footer,
  WhatsApp button are missing). Then switch back to `mebeauty`.
- [ ] **T-TH-05** Change the company name to `Test & „Co“ <b>`: browser tab, backend header and the subject of a
  booking mail show the name literally, nothing is bold. Then change it back to "MeBeauty".
- [ ] **T-TH-06** Upload a company logo: it appears in the backend header and in a new booking mail. Without a logo:
  generic logo.
- [ ] **T-TH-07** "Firmenkopf auf Buchungsseite anzeigen" (booking settings) on: logo and name in the header of the
  Wizard-Card; off: no header.
- [ ] **T-TH-08** Colour chain: without a company colour (white), success page, step indicator and mail buttons are
  gold; with a company colour, e.g. blue, they are blue; with theme `default` and no company colour, green.
- [ ] **T-TH-09** Error page (<http://localhost/index.php/gibtsnicht>) shows a brand title, no "Easy!Appointments".
- [ ] **T-TH-10** Language set to English (`?language=english`): navbar and footer texts are English.

## 0003 Booking deadline, buffer, time zone — 0003, 0012

- [ ] **T-FR-01** Deadline mode "Feste Uhrzeit", deadline time = now + 5 min, day offset 1: tomorrow is bookable.
  After the 5 min have passed, reload the page: tomorrow is greyed out in the date picker.
- [ ] **T-FR-02** Day offset 0, deadline time = one minute ago: today is blocked, tomorrow is bookable.
- [ ] **T-FR-03** Deadline mode "Minuten-Vorlaufzeit" with 120: today only offers times from now + 2 h.
- [ ] **T-FR-04** In the backend calendar an appointment for today can still be created despite the expired
  deadline.
- [ ] **T-PU-01** Buffer time 30 (business logic), appointment S60 with A tomorrow 10:00: A offers 11:30 at the
  earliest; 09:00 (ends flush before the appointment) stays bookable.
- [ ] **T-PU-02** Flush before the break: S60 at 11:00 with A is offered.
- [ ] **T-PU-03** "Puffer auch am Tagesende anwenden" on: last S60 slot with A is 16:30; off: 17:00.
- [ ] **T-PU-04** Working plan exception for A tomorrow until 15:00, option on: last S60 slot 13:30.
- [ ] **T-PU-05** Unavailability for A tomorrow 15:00–18:00, option on: S60 at 14:00 stays bookable.
- [ ] **T-TZ-01** "Zeitzonen-Anzeige" = "Dropdown und Bestätigungs-Frame": dropdown in the time step and a line in the
  confirmation step.
- [ ] **T-TZ-02** "Nur im Bestätigungs-Frame": no dropdown, line in the confirmation.
- [ ] **T-TZ-03** "Komplett ausblenden" with source "Browser-Zeitzone": DevTools → Sensors → Location "Tokyo": the
  offered times are shifted to Tokyo time. With source "Firmen-Zeitzone": times as in the company time zone.

Automated: deadline, buffer and end-of-working-day edge cases (PHPUnit `Booking_policy`, Availability).

## 0006 Slot capacity — 0006

- [ ] **T-KA-01** S60 slot capacity 1 (services): book S60 tomorrow 10:00 with A. Afterwards B offers nothing for S60
  at 10:00 (nor overlapping 09:30); neither does "Egal bei wem".
- [ ] **T-KA-02** Capacity 2: B still offers 10:00; after the second booking it no longer does.
- [ ] **T-KA-03** Capacity 0: unlimited, as upstream.
- [ ] **T-KA-04** In the backend calendar a third S60 at 10:00 can be created despite full capacity.
- [ ] **T-KA-05** Save capacity "abc", "1.5" or "-1": error message, nothing saved.

## 0006 Manage link: rescheduling and cancelling — 0006, 0009

Manage link = the appointment link from an appointment's customer mail (Mailpit).

- [ ] **T-MA-01** Within the deadline: open the manage link, reschedule to another time. The old time is offered as
  free; afterwards exactly one appointment exists (no duplicate).
- [ ] **T-MA-02** Cancel via the manage link: appointment gone, cancellation mail in Mailpit.
- [ ] **T-MA-03** Reschedule/cancellation deadline "eigene Frist" in minutes = 1440, appointment in less than 24 h:
  the manage link shows the lock page with the concrete limit. Appointment in more than 24 h: can be rescheduled.
- [ ] **T-MA-04** Manage deadline "Feste Uhrzeit" now + 3 min, day offset 1, appointment tomorrow: open the manage
  link and leave it open, let the deadline expire, then submit: rejected with the lock message, appointment unchanged.
- [ ] **T-MA-05** Manage link with an altered hash (change the last character): "Termin nicht gefunden", no
  rescheduling.
- [ ] **T-MA-06** In the backend the locked appointment can still be moved.

Automated: hash/ID binding and requests without a hash (PHPUnit, 0009).

## 0010 Settings validation — 0010

- [ ] **T-VA-01** Booking settings: save deadline time "25:00" → error message at the field, **none of the other**
  changed values are saved either (reload the page).
- [ ] **T-VA-02** Day offset "-1" or buffer time "x" → error message, nothing saved.

Automated: all rules and the API path (PHPUnit, 0010).

## 0017 Appointment contact — 0017/04, 0017/05

- [ ] **T-KO-01** Book online with `anna@example.com`, name "Anna Alt", phone 111. Then book again with the same
  email, name "Lena Neu", phone empty. In the calendar the first appointment shows "Anna Alt", the second
  "Lena Neu"; the customer record keeps phone 111.
- [ ] **T-KO-02** The salon mail of the second appointment shows "Lena Neu".
- [ ] **T-KO-03** Appointment dialog (backend): exactly one customer block, prefilled with the appointment's contact
  data.
- [ ] **T-KO-04** In the dialog of the first appointment change the phone to 222 and save: appointment and customer
  record show 222; the second appointment stays unchanged.
- [ ] **T-KO-05** Move an appointment by drag & drop: contact data is kept.
- [ ] **T-KO-06** Create a backend appointment for a new customer: the tile shows their name.
- [ ] **T-KO-07** Manage link of the first appointment: enter another email `fremd@example.com` and reschedule
  (without email verification). The appointment still belongs to Anna; if no other customer uses the address,
  Anna's record takes it over.
- [ ] **T-KO-08** **[Test server]** The Google event of an online booking starts with the block `--- Kontakt ---`
  (name, phone, email); on a phone the number is tappable.
- [ ] **T-KO-09** **[Test server]** Add a note below the block in the Google event, trigger a sync: the note appears
  in the app appointment, the contact block does not.

## 0018 Email verification and hold — 0018/01–05

Setting "E-Mail-Bestätigung erforderlich" (booking settings) on.

- [ ] **T-EB-01** Book online: notice "Bitte bestätigen Sie Ihre E-Mail-Adresse" with "E-Mail erneut senden". Mailpit
  only contains the verification mail, no customer or salon mail. In the backend calendar the appointment shows as
  "unbestätigt".
- [ ] **T-EB-02** During the hold, a second browser window (private) does not offer the slot.
- [ ] **T-EB-03** Open the link in the same browser session: success page directly, without a button. Afterwards
  customer and salon mail in Mailpit, appointment without "unbestätigt".
- [ ] **T-EB-04** New booking, open the link in a private window (other device): page "Termin bestätigen" with a
  button. Click → success page.
- [ ] **T-EB-05** Open the same link a second time: "Link ungültig".
- [ ] **T-EB-06** After the verification, book a second time in the same session with the same email: booked
  immediately, no verification mail.
- [ ] **T-EB-07** Open an unverified appointment in the backend: notice, only deleting is possible. Deleting sends
  no mail.
- [ ] **T-EB-08** Resend: right after submitting → wait notice with seconds. After 60 s → new mail, the link of the
  old mail is "ungültig" afterwards.
- [ ] **T-EB-09** Late click, slot free: create a hold, in phpMyAdmin run
  `UPDATE ea_appointment_verifications SET expires_datetime = NOW() - INTERVAL 1 MINUTE;`, reload the booking flow
  (cleans up, slot free again, the unverified appointment disappears from the calendar). Then open the link and
  confirm → appointment booked.
- [ ] **T-EB-10** Late click, slot taken: as T-EB-09, but before clicking book the slot in another window → page
  "Link abgelaufen" with a link to the booking.
- [ ] **T-EB-11** Invalid after 24 h: as T-EB-09, additionally
  `last_sent_datetime = NOW() - INTERVAL 25 HOUR` → "Link ungültig".
- [ ] **T-EB-12** Book in English: verification mail and pages in English.
- [ ] **T-EB-13** Rescheduling via the manage link without changing the email: rescheduled immediately, no
  verification mail.
- [ ] **T-EB-14** Rescheduling with a new email (private window): the new time applies immediately, the reschedule
  mail goes to the **old** address, the verification mail "neue E-Mail-Adresse" to the new one. After verification,
  appointment and record show the new address.
- [ ] **T-EB-15** **[Test server]** The hold does not appear in Google Calendar, only after the verification.
- [ ] **T-EB-16** Cleanup via console: an expired hold (as T-EB-09) disappears after

  ```bash
  docker compose exec -T php-fpm php index.php console cleanup
  ```

Automated: hourly limit (5 mails/h), cooldown, hold time 20 min, neutral error messages (PHPUnit
`Email_verification_rules`).

## 0019 Footer and language selector — 0014, 0019/01

- [ ] **T-FO-01** "Sprachauswahl im Buchungsformular anzeigen" off: no language switcher in the booking flow.
- [ ] **T-FO-02** Footer on, but no legal URL, language selector off, login button off: no footer and no divider
  below the Wizard-Card.
- [ ] **T-FO-03** Footer on with login button on: footer with login button visible.
- [ ] **T-FO-04** "Footer anzeigen" off (login button on): no footer. The theme's website footer stays. The backend
  is reachable via <http://localhost/index.php/login>.

## 0020 Wizard step scroll — 0020/01

Run each test case with theme `mebeauty` and again with theme `default`, on desktop and at phone width (browser
dev tools, e.g. 390×844).

- [ ] **T-WS-01** Open the booking flow (<http://localhost>): the page opens at the top, navbar and hero image are
  visible as before.
- [ ] **T-WS-02** Select S60 and A, click "Weiter": the page does not show navbar and hero again; the top of the
  Wizard-Card of step 2 sits just below the top of the screen and the card is visible without scrolling (as far as
  it fits on the screen).
- [ ] **T-WS-03** Scroll so that the whole Wizard-Card is visible (desktop, short step), then click "Weiter" or
  "Zurück": if the new step is fully visible as well, the page does not move.
- [ ] **T-WS-04** At phone width, scroll down to the "Weiter" button at the bottom of a step taller than the screen
  (step 2 with the time list, or step 3 with the customer form): after the click, the top of the next step is
  visible.
- [ ] **T-WS-05** "Zurück" from the bottom of a tall step: the top of the previous step is visible, not its middle.
- [ ] **T-WS-06** Step changes are instant (no scroll animation) and the page does not jump or flicker while the
  steps fade.
- [ ] **T-WS-07** Preselected link, e.g. <http://localhost/?service=ID&provider=ID> with the IDs of S60 and A from
  the backend: the page opens at step 2 and at the top, navbar and hero visible as before.
- [ ] **T-WS-08** Manage link of an existing appointment (from the customer mail): "Weiter" and "Zurück" behave as
  in T-WS-02 to T-WS-05.

## 0021 Backend header — 0021/01

- [ ] **T-BH-01** Backend with calendar view "Standard" (account settings → "Kalender"): clicking the logo, the
  company name or the subtitle at the top left opens the calendar (<http://localhost/index.php/calendar>), the same
  page as the "Kalender" menu item. The header looks unchanged: no underline, no colour change on hover.
- [ ] **T-BH-02** Calendar view set to "Tabelle": clicking the logo opens the table view (`calendar?view=table`),
  like the "Kalender" menu item.
- [ ] **T-BH-03** Phone width (DevTools, 375 px): the logo link sits next to the collapsed menu button and opens the
  calendar; the menu button still opens the menu.

## 0023 Customer double booking — 0023/01, 0023/02

All bookings with the same customer email. The overlap rule itself (partial overlaps, back-to-back, "any provider",
Manage-Link, Vormerkung) is automated (`tests/smoke/customer-overlap-smoke.sh`, case 12b of
`tests/smoke/email-verification-smoke.sh`).

- [ ] **T-DB-01** Book S60 with A tomorrow at 10:00. Book again S60 with B tomorrow at 10:30 (or "Egal
  bei wem") and submit: dialog titled "Termin nicht möglich" with the text "Sie haben zu dieser Zeit bereits einen
  Termin bei uns. Bitte wählen Sie eine andere Uhrzeit." No "Der Vorgang konnte nicht abgeschlossen werden." and no
  grey box with a server message. (0023/02)
- [ ] **T-DB-02** Close the dialog: the wizard still shows the confirmation step with all entered data. Go back,
  choose 11:00 and submit: booked (back-to-back). (0023/01, 0023/02)
- [ ] **T-DB-03** Switch the booking page to English and repeat T-DB-01: title "Appointment not possible", text "You
  already have an appointment with us at this time. Please choose another time." (0023/02)
- [ ] **T-DB-04** Slot taken in the meantime: open the booking flow in two windows (one private), choose the same
  free slot with different emails up to the confirmation step, book in the first window, then submit in the second:
  the same dialog "Termin nicht möglich" with the text "Diese Uhrzeit ist leider nicht mehr frei. …".
  (0023/02)
- [ ] **T-DB-05** Unexpected error on the booking page: in the DevTools console run
  `$.post(App.Utils.Url.siteUrl('booking/register'), {csrf_token: vars('csrf_token')})`: dialog titled with the
  company name (General settings → "Name der Firma"), not "Easy!Appointments", with the text "Der Vorgang konnte nicht
  abgeschlossen werden." and the server message in a box below. (0023/02)
- [ ] **T-DB-06** Unexpected error in the backend calendar: in the DevTools console run
  `$.post(App.Utils.Url.siteUrl('calendar/save_appointment'), {csrf_token: vars('csrf_token')})`: dialog titled with
  the company name as in T-DB-05. (0023/02)

## 0025 German translation revision — 0025/02

Theme `mebeauty`, booking page in German (<http://localhost>) unless a test case says otherwise. Wording follows
`docs/translation-style-de.md`.

- [ ] **T-DE-01** Step 1: heading "Dienstleistung und Person auswählen", label "Dienstleistung", first option "Bitte
  auswählen". After choosing S60 the second field is labelled "Bei wem möchten Sie buchen?" and offers A, B and
  "Egal bei wem" (booking settings: the "any provider" switch, `display_any_provider`, on). No "Anbieter" anywhere
  in the step. (0025/02)
- [ ] **T-DE-02** Step indicator tooltips (hover the numbers 1–4): "Dienstleistung und Person auswählen", "Datum und Uhrzeit
  auswählen", "Ihre Angaben", "Termin bestätigen"; the step headings read the same. (0025/02)
- [ ] **T-DE-03** Step 2: click "Weiter" without choosing a time: "Bitte wählen Sie eine Uhrzeit, bevor Sie
  fortfahren." When the preselected day has no free times (e.g. open the page on a weekday after 18:00): "An
  diesem Tag sind leider keine Termine frei. Bitte wählen Sie ein anderes Datum." (0025/02)
- [ ] **T-DE-04** Step 3: click "Weiter" with empty required fields: "Felder mit * sind Pflichtfelder." (0025/02)
- [ ] **T-DE-05** Step 4: heading "Termin bestätigen", right column "Ihre Kontaktdaten", submit button
  "Bestätigen". With "Egal bei wem" the left column shows "Egal bei wem" under the service. (0025/02)
- [ ] **T-DE-06** CAPTCHA on (booking settings "CAPTCHA erfordern", ALTCHA settings "ALTCHA aktivieren" on):
  button "Ich bin kein Roboter" with the text "Bitte klicken Sie, um die Sicherheitsprüfung zu starten."; after the
  click "Wird geprüft...", then "Geprüft" and "Sicherheitsprüfung abgeschlossen". Submitting without the check: "Die
  Sicherheitsprüfung ist fehlgeschlagen. Bitte versuchen Sie es erneut." With ALTCHA off and a wrong image code: "Der
  eingegebene Code ist falsch. Bitte versuchen Sie es erneut." (0025/02)
- [ ] **T-DE-07** Refusal dialogs: T-DB-01 and T-DB-04 show the texts named there ("Diese Uhrzeit ist leider nicht
  mehr frei. Bitte wählen Sie eine andere Uhrzeit." for a slot taken in the meantime). (0025/02)
- [ ] **T-DE-08** Email verification on: after submitting, the notice "Bitte bestätigen Sie Ihre E-Mail-Adresse"
  reads naturally and uses "Sie". (0025/02)
- [ ] **T-DE-09** No bookable service (unassign S60 and S30 from A and B): title "Online-Buchung derzeit nicht
  möglich", text "Zurzeit können keine Termine online gebucht werden. …". Booking disabled (booking settings): title
  "Online-Buchung ist derzeit nicht möglich", browser tab "Termin buchen bei <company name> | <company name>". Restore
  afterwards. (0025/02)
- [ ] **T-DE-10** With "Cookie-Hinweis anzeigen" on (booking settings) and empty site data, the cookie notice reads
  "Diese Website verwendet Cookies, damit Sie sie bestmöglich nutzen können." (0025/02)
- [ ] **T-DE-11** Theme: the WhatsApp button's accessible name (`aria-label`, DevTools) reads "Schreiben Sie uns auf
  WhatsApp". (0025/02)
- [ ] **T-DE-12** Switch the booking page to English: step 1 asks "Who would you like to book with?" and offers
  "Any Provider"; the other texts are unchanged. (0025/02)

Emails (0025/03): all mails in Mailpit (<http://localhost:8025>), customer and staff with language German
(provider A: backend → providers page → A → "Sprache" German, notifications on; booking settings
"Kundenbenachrichtigungen" on).

- [ ] **T-DE-13** Book online in German: the customer mail is titled "Ihr Termin ist gebucht" (subject and heading),
  text "Vielen Dank für Ihre Buchung. … Über „Termin verwalten“ können Sie ihn umbuchen oder stornieren.", button
  "Termin verwalten". In the mail's HTML source (Mailpit → "HTML Source") the root element reads `<html lang="de">`.
  (0025/03)
- [ ] **T-DE-14** Same booking, staff mail to A: subject and heading "Neuer Termin gebucht", text "Über „Termin
  verwalten“ öffnen Sie den Termin im Kalender."; the button opens the appointment in the backend calendar.
  (0025/03)
- [ ] **T-DE-15** Reschedule via the manage link: customer mail "Ihr Termin wurde geändert", staff mail "Termin
  geändert", no text line below the heading. (0025/03)
- [ ] **T-DE-16** Cancel via the manage link with a reason: customer and staff mail "Termin storniert", text "Der
  folgende Termin wurde storniert.", the reason below the details. (0025/03)
- [ ] **T-DE-17** Email verification on: the verification mail reads "Bitte bestätigen Sie Ihren Termin" / "Nur noch
  ein Schritt: Bestätigen Sie Ihre E-Mail-Adresse, dann ist Ihr Termin gebucht.", button "Termin bestätigen", note
  "Wir halten den Termin 10 Minuten für Sie frei. …". The link page says "Klicken Sie auf „Termin bestätigen“, um
  Ihre Buchung abzuschließen." above a button with the same label. Expired and taken (T-EB-10): "Link abgelaufen";
  not bookable: "Termin nicht gebucht" / "Dieser Termin konnte leider nicht gebucht werden. Bitte wählen Sie eine
  andere Uhrzeit." (0025/03)
- [ ] **T-DE-18** Reschedule with a new email (T-EB-14): verification mail "Bitte bestätigen Sie Ihre neue
  E-Mail-Adresse", the link page names the button "E-Mail-Adresse bestätigen" exactly as the button reads. (0025/03)
- [ ] **T-DE-19** Login page → "Passwort vergessen?" for a backend user with language German: mail "Passwort
  zurücksetzen", text names the button "Passwort zurücksetzen", note "Der Link ist eine Stunde lang gültig."
  (0025/03)
- [ ] **T-DE-20** Book in English (language switch on the booking page, customer language English): customer mail in
  English with `<html lang="en">`, while the staff mail to A (German) is German with `<html lang="de">`. (0025/03)

Manage-Link page (0025/04): manage link from the customer mail (Mailpit), booking page in German; the delete bar
needs "Persönliche Daten löschen" on in the booking settings.

- [ ] **T-DE-21** The yellow bar reads "Sie können den Termin nicht wahrnehmen? Klicken Sie auf „Termin
  stornieren“." next to a button labelled exactly "Termin stornieren". (0025/04)
- [ ] **T-DE-22** Click "Termin stornieren": dialog "Termin stornieren", text "Bitte sagen Sie uns kurz, warum Sie
  den Termin stornieren. Ein Stichwort genügt.", buttons "Termin behalten" (closes the dialog, appointment stays)
  and "Termin stornieren" (with an empty reason the field is marked red and nothing happens). (0025/04)
- [ ] **T-DE-23** Cancel with a reason: page "Termin storniert", text "Ihr Termin wurde storniert.", button "Weiter zur
  Buchungsseite". (0025/04)
- [ ] **T-DE-24** Reschedule: the last step's button reads "Umbuchung bestätigen" (a new booking still reads
  "Bestätigen"). (0025/04)
- [ ] **T-DE-25** Lock page (as T-MA-03, "eigene Frist" in minutes): title "Umbuchen und Stornieren nicht mehr
  möglich", text "Ein Termin kann bis 24 Stunden vor Beginn umgebucht oder storniert werden." (with 90 minutes:
  "… bis 1 Stunde 30 Minuten vor Beginn …"); with "Feste Uhrzeit" (T-MA-04): "Der Termin kann nicht mehr umgebucht
  oder storniert werden. Das war bis … möglich." (0025/04)
- [ ] **T-DE-26** Altered hash (T-MA-05): "Termin nicht gefunden" / "Diesen Termin gibt es nicht mehr. Vielleicht
  wurde er bereits storniert oder der Link ist unvollständig." (0025/04)
- [ ] **T-DE-27** Red bar: "Löschen Sie alle Ihre Termine und persönlichen Daten bei uns." with button "Löschen";
  the dialog "Persönliche Daten löschen" asks "Möchten Sie Ihre persönlichen Daten und alle Ihre Termine wirklich
  löschen? Das lässt sich nicht rückgängig machen." with "Abbrechen" / "Löschen". (0025/04)
- [ ] **T-DE-28** Same manage link in English: the hint names "Cancel appointment", the button reads "Cancel
  appointment"; the dialog offers "Keep appointment" / "Cancel appointment". (0025/04)

Backend (0025/05): log in as admin, backend language German (account settings). Wording follows
`docs/translation-style-de.md`; no "Anbieter", "Dienstleister", "Sekretär", "Bearbeiter" or "Service" anywhere.

- [ ] **T-DE-29** Login page: heading "Verwaltung", text "Willkommen! Bitte melden Sie sich an, um die Verwaltung zu
  öffnen."; booking footer while logged in: button "Verwaltung". (0025/05)
- [ ] **T-DE-30** Menu "Benutzer": entries "Mitarbeitende", "Assistenz", "Admins"; each page title matches. On
  "Mitarbeitende" delete a test record: dialog "Mitarbeiterin/Mitarbeiter löschen" (not "Zeitraum löschen"), message
  "Mitarbeiterin/Mitarbeiter gelöscht."; on "Assistenz" saving shows "Assistenz gespeichert.". (0025/05)
- [ ] **T-DE-31** Calendar: filter list groups "Mitarbeitende" and "Dienstleistungen"; appointment dialog and event
  popover label "Mitarbeiterin/Mitarbeiter" and "Kundin/Kunde"; "Abwesenheit" in the add menu and its dialog "Neue
  Abwesenheit"; off-hours read "Arbeitsfrei". A customer mail shows the label "Mitarbeiterin/Mitarbeiter". (0025/05)
- [ ] **T-DE-32** Settings → Buchungseinstellungen: the switch reads "Option „Egal bei wem“ anzeigen" with the hint
  naming „Egal bei wem“ as in the booking step; field table columns "Anzeigen" / "Pflichtfeld". (0025/05)
- [ ] **T-DE-33** Settings → Geschäftsregeln (formerly "Unternehmerische Logik"): hints of the working plan
  and breaks speak of "Mitarbeitende"; "Für alle Mitarbeitenden übernehmen" asks "Damit werden die Arbeitspläne
  aller Mitarbeitenden überschrieben. Möchten Sie fortfahren?"; "Pause hinzufügen" button. (0025/05)
- [ ] **T-DE-34** Settings → Integrationen → Webhooks: the event list reads "Dienstleistung speichern",
  "Mitarbeiterin/Mitarbeiter speichern", "Assistenz speichern", "Admin speichern" (no "Sekretär Save", "Service
  speichern", "Dienst löschen"). (0025/05)
- [ ] **T-DE-35** Settings → Rechtliche Inhalte: section "Rechtliche Hinweise" with field "URL der Seite „Rechtliche
  Hinweise“"; with the URL set, the booking footer link reads "Rechtliche Hinweise". (0025/05)
