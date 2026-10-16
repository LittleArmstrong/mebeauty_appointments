# Manual Tests

Checklist for the manual run before each release. It covers what has to be seen in the browser, in mails or in the
calendar. Limits and rules already checked by PHPUnit or the smoke tests (`bash scripts/test.sh`) are only listed as
"Automated".

UI labels are quoted as they appear in the German UI.

**Maintenance:** Every spec with visible behaviour adds its test cases here (one test case per observable
acceptance criterion, with a ticket reference). Tick the boxes only in your local copy; the empty template is what
gets committed.

**Feedback:** Report failures with the test case ID, e.g. "T-EB-03 fails: …". Passed sections verify the tickets
named in their heading.

Duration: about 1–2 hours for a complete run.

## 0 Preparation

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

## 1 Basic run (upstream core functionality)

- [ ] **T-B-01** Booking flow (<http://localhost>): book S60 with A. The success page appears, customer and salon
  mail in Mailpit, appointment in the backend calendar.
- [ ] **T-B-02** In the backend calendar, create an appointment for an existing customer, move it and delete it.
  Each notification mail is written in its recipient's language (user profile setting): e.g. the seeded customer
  James has English and gets English mails, while admin and provider with German get German ones.

## 2 Theme, branding, site chrome — 0001, 0004, 0005, 0014

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

## 3 Booking deadline, buffer, time zone — 0003, 0012

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

## 4 Slot capacity — 0006

- [ ] **T-KA-01** S60 slot capacity 1 (services): book S60 tomorrow 10:00 with A. Afterwards B offers nothing for S60
  at 10:00 (nor overlapping 09:30); neither does "Beliebiger Anbieter".
- [ ] **T-KA-02** Capacity 2: B still offers 10:00; after the second booking it no longer does.
- [ ] **T-KA-03** Capacity 0: unlimited, as upstream.
- [ ] **T-KA-04** In the backend calendar a third S60 at 10:00 can be created despite full capacity.
- [ ] **T-KA-05** Save capacity "abc", "1.5" or "-1": error message, nothing saved.

## 5 Manage link: rescheduling and cancelling — 0006, 0009

Manage link = the appointment link from an appointment's customer mail (Mailpit).

- [ ] **T-MA-01** Within the deadline: open the manage link, reschedule to another time. The old time is offered as
  free; afterwards exactly one appointment exists (no duplicate).
- [ ] **T-MA-02** Cancel via the manage link: appointment gone, cancellation mail in Mailpit.
- [ ] **T-MA-03** Reschedule/cancellation deadline "eigene Frist" in minutes = 1440, appointment in less than 24 h:
  the manage link shows the lock page with the concrete limit. Appointment in more than 24 h: can be rescheduled.
- [ ] **T-MA-04** Manage deadline "Feste Uhrzeit" now + 3 min, day offset 1, appointment tomorrow: open the manage
  link and leave it open, let the deadline expire, then submit: rejected with the lock message, appointment unchanged.
- [ ] **T-MA-05** Manage link with an altered hash (change the last character): "Termin nicht gefunden.", no
  rescheduling.
- [ ] **T-MA-06** In the backend the locked appointment can still be moved.

Automated: hash/ID binding and requests without a hash (PHPUnit, 0009).

## 6 Settings validation — 0010

- [ ] **T-VA-01** Booking settings: save deadline time "25:00" → error message at the field, **none of the other**
  changed values are saved either (reload the page).
- [ ] **T-VA-02** Day offset "-1" or buffer time "x" → error message, nothing saved.

Automated: all rules and the API path (PHPUnit, 0010).

## 7 Appointment contact — 0017/04, 0017/05

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

## 8 Email verification and hold — 0018/01–05

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
  "Leider abgelaufen" with a link to the booking.
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

## 9 Footer and language selector — 0014, 0019/01

- [ ] **T-FO-01** "Sprachauswahl im Buchungsformular anzeigen" off: no language switcher in the booking flow.
- [ ] **T-FO-02** Footer on, but no legal URL, language selector off, login button off: no footer and no divider
  below the Wizard-Card.
- [ ] **T-FO-03** Footer on with login button on: footer with login button visible.
- [ ] **T-FO-04** "Footer anzeigen" off (login button on): no footer. The theme's website footer stays. The backend
  is reachable via <http://localhost/index.php/login>.

## 10 Backend header — 0021/01

- [ ] **T-BH-01** Backend with calendar view "Standard" (account settings → "Kalender"): clicking the logo, the
  company name or the subtitle at the top left opens the calendar (<http://localhost/index.php/calendar>), the same
  page as the "Kalender" menu item. The header looks unchanged: no underline, no colour change on hover.
- [ ] **T-BH-02** Calendar view set to "Tabelle": clicking the logo opens the table view (`calendar?view=table`),
  like the "Kalender" menu item.
- [ ] **T-BH-03** Phone width (DevTools, 375 px): the logo link sits next to the collapsed menu button and opens the
  calendar; the menu button still opens the menu.
