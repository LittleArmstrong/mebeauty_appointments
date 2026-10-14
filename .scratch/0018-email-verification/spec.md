# E-Mail-Bestätigung mit Vormerkung

Status: needs-verification
Created: 2026-10-13
Closed: –
Priority: P1
Depends-on: 0017

## Problem Statement

Eine Online-Buchung prüft nicht, ob die eingegebene E-Mail-Adresse der buchenden Person gehört. Direkt nach dem Speichern verschickt sie Bestätigung und Manage-Link an diese Adresse und synchronisiert den Termin nach Google (`application/controllers/Booking.php:573-582`).

- Ein Tippfehler bei der E-Mail führt dazu, dass die Bestätigung und der Manage-Link an eine fremde Adresse gehen. Die buchende Person bekommt nichts und kann ihren Termin nicht umbuchen oder stornieren.
- Wer die E-Mail eines anderen Kunden einträgt, wird diesem Kunden zugeordnet und aktualisiert dessen Datensatz (Restrisiko aus 0017).
- Bei einer Umbuchung lässt sich die E-Mail ändern, ohne dass jemand die neue Adresse bestätigt.

## Solution

Eine neue Einstellung „E-Mail-Bestätigung erforderlich“ (Standard: aus, wie bei Upstream). Ist sie an, wird ein Online-Termin zunächst **vorgemerkt**: Er hält den Slot, und an die eingegebene Adresse geht ein Bestätigungslink. Erst der Klick bestätigt den Termin. Dann wird der Kunde zugeordnet oder angelegt, der Datensatz nach den Regeln aus 0017 aktualisiert, und Bestätigungsmail, Salon-Mail und Google-Sync laufen an. Der Link ist 10 Minuten gültig. Kommt der Klick später und ist der Slot noch frei, wird trotzdem gebucht. Ist der Slot inzwischen vergeben, erscheint der Hinweis „abgelaufen“.

Bei einer Umbuchung über den Manage-Link gelten neue Zeit und übrige Angaben sofort. Nur eine geänderte E-Mail wartet auf den Bestätigungslink an die neue Adresse.

## User Stories

1. Als Salonbetreiberin will ich die E-Mail-Bestätigung in den Buchungseinstellungen einschalten können, damit jede Online-Buchung eine erreichbare Adresse hat.
2. Als Admin einer Installation ohne Anpassung will ich, dass die Bestätigung standardmäßig aus ist, damit sich das Upstream-Verhalten nicht ändert.
3. Als Kundin will ich nach dem Absenden sehen, dass mein Termin für 10 Minuten reserviert ist und ich den Link in meiner Mail klicken muss.
4. Als Kundin will ich, dass der Link mich direkt zur bekannten Erfolgsseite mit meinem Termin führt.
5. Als Kundin will ich den Link auch auf einem anderen Gerät öffnen können, z. B. am Handy, während ich am Laptop gebucht habe.
6. Als Kundin will ich die Mail erneut anfordern können, wenn sie nicht ankommt.
7. Als Kundin will ich, dass mein Slot während der 10 Minuten nicht an jemand anderen geht.
8. Als Kundin, die den Link zu spät klickt, will ich trotzdem gebucht werden, wenn der Slot noch frei ist.
9. Als Kundin, deren Slot inzwischen vergeben ist, will ich eine verständliche Meldung und einen Weg zur neuen Buchung.
10. Als Kundin will ich nach einer Bestätigung in derselben Browser-Sitzung direkt ein zweites Mal buchen können, ohne erneut zu bestätigen.
11. Als Inhaberin einer E-Mail-Adresse will ich höchstens wenige Bestätigungsmails pro Stunde bekommen, damit niemand mein Postfach zuspammt.
12. Als Salonbetreiberin will ich, dass eine Buchung mit fremder E-Mail ohne Zugriff auf das Postfach weder einen Termin bestätigt noch einen Datensatz ändert.
13. Als Salonbetreiberin will ich, dass unbestätigte Termine nach Ablauf den Slot wieder freigeben.
14. Als Mitarbeiterin will ich vorgemerkte Termine im Backend-Kalender als „unbestätigt“ sehen, damit ich den Slot telefonisch nicht doppelt vergebe.
15. Als Mitarbeiterin will ich, dass vorgemerkte Termine nicht in Google erscheinen, solange sie nicht bestätigt sind.
16. Als Salonbetreiberin will ich die Salon-Mail erst bei einem bestätigten Termin bekommen.
17. Als Kundin mit Manage-Link will ich bei einer Umbuchung meine neue Zeit sofort sicher haben, auch wenn ich die E-Mail ändere.
18. Als Kundin mit Manage-Link will ich meine neue E-Mail per Link bestätigen, damit sie übernommen wird.
19. Als Kundin will ich, dass Mails bis zur Bestätigung weiter an meine alte, bestätigte Adresse gehen.
20. Als Kundin mit Manage-Link will ich ohne Bestätigung umbuchen können, wenn ich meine E-Mail nicht ändere.
21. Als Kundin will ich die Bestätigungsmail in der Sprache meiner Buchung bekommen (Deutsch oder Englisch).
22. Als Admin will ich, dass abgelaufene Vormerkungen ohne Cronjob aufgeräumt werden, und zusätzlich über `console cleanup`.
23. Als Admin will ich, dass ein Bestätigungslink nur einmal funktioniert.
24. Als Entwickler will ich die Ablauf- und Limit-Regeln als reine Funktionen testen können.

## Implementation Decisions

- **Einstellung** `require_email_verification`:
  - Werte 0/1, Standard 0. Sie steht in den Buchungseinstellungen und wird über den zentralen Regel-Mechanismus aus 0010 als 0/1 geprüft.
  - Angelegt wird sie in Migration 070, nach dem Konsolidierungsprinzip aus 0017.
  - Für MeBeauty wird sie beim Deployment auf 1 gesetzt, nicht per Migration.
- **Vormerkung** (Glossar):
  - Ein normaler Termin in `appointments`. Dadurch blockiert er den Slot ohne Änderung an der Verfügbarkeitsberechnung und zählt für die Slot-Kapazität (0006).
  - Neue Fork-Tabelle `appointment_verifications`. Sie markiert den Termin als vorgemerkt und enthält:
    - Bezug zum Termin
    - Zweck (Neubuchung bzw. E-Mail-Änderung)
    - zu bestätigende E-Mail
    - Hash des Tokens (der Token selbst wird nicht gespeichert)
    - Ablaufzeit
    - Zeitpunkt der letzten Mail
    - Zähler
    - die zum Wiederherstellen nötigen Buchungsdaten: Service, Dienstleister, Beginn, Ende, Notizen und die Kundendaten aus dem Formular
  - Bis zur Bestätigung hat der Termin keinen Kunden. Die Eingaben stehen in den Termin-Kontaktdaten (0017).
- **Ablauf der Neubuchung bei aktiver Einstellung:**
  - `register` legt den vorgemerkten Termin an und verschickt die Bestätigungsmail. Die Antwort meldet „vorgemerkt“, der Wizard zeigt einen Hinweis mit „erneut senden“.
  - Keine Kundenmail, keine Salon-Mail, kein Google-Sync, kein Webhook.
  - Ist die E-Mail in dieser Browser-Sitzung schon bestätigt, wird sofort gebucht wie ohne Einstellung.
- **Bestätigungs-Endpunkt** (öffentlich, Token im Link):
  - Das Öffnen des Links (`GET booking/verify/<token>`) ändert nichts, weil Mail-Scanner Links öffnen. Es zeigt eine
    Seite „Termin bestätigen“ (bzw. „E-Mail-Adresse bestätigen“) mit einer Schaltfläche. Erst die Schaltfläche
    (`POST booking/confirm_verification` mit CSRF-Token) bestätigt. Ein unbekannter oder verfallener Link zeigt gleich
    „Link ungültig“.
  - Ausnahme: In der Browser-Sitzung, die genau diese Bestätigung begonnen hat (Absenden der Buchung bzw. Umbuchung
    mit E-Mail-Änderung), bestätigt schon das Öffnen des Links, ohne Schaltfläche und mit denselben Ergebnissen
    (Erfolgsseite, „abgelaufen“, „Termin nicht gebucht“). Die Sitzung merkt sich die IDs ihrer Bestätigungen (höchstens
    20), nicht die Adresse; ein Link einer anderen Bestätigung zeigt auch dort die Schaltfläche. Die Entscheidung trifft
    `Email_verification_rules::confirms_on_open()`, `Booking::verify()` ruft nur `Email_verification::open_link()` auf.
    Mail-Apps mit eigenem In-App-Browser haben die Sitzung nicht und zeigen die Schaltfläche.
  - Prüft den Token und nimmt ihn auf. Die Markierung bleibt dabei gesperrt (`SELECT … FOR UPDATE`), so dass ein
    gleichzeitiger Klick, „erneut senden“ oder das Aufräumen nicht dazwischenkommt; die Verfügbarkeitsprüfung beim
    späten Klick läuft in derselben Transaktion.
  - Hat der Kunde der E-Mail zu dieser Zeit schon einen Termin (Prüfung `customer_is_already_booked` des
    Buchungsformulars), wird die Vormerkung verworfen und eine neutrale Seite „Termin nicht gebucht“ gezeigt. Bei
    aktiver Bestätigung läuft diese Prüfung nur hier und nicht beim Absenden, damit die Antwort des Formulars nicht
    verrät, ob eine Adresse einem Kunden gehört.
  - Ordnet den Kunden zu bzw. legt ihn an und aktualisiert den Datensatz nach 0017.
  - Entfernt die Markierung, startet Sync, Mails und Webhook wie bei einer normalen Buchung und leitet auf die bestehende Erfolgsseite weiter.
  - Merkt die Adresse für die Browser-Sitzung als bestätigt.
- **Nach Ablauf (10 Minuten):**
  - Der vorgemerkte Termin wird gelöscht und gibt den Slot frei. Die Wiederherstellungsdaten bleiben 24 Stunden nach
    der letzten Mail erhalten. Nur dieses Aufräumen markiert sie als „wiederherstellbar“: Eine Vormerkung, die anders
    gelöscht wird (Personal, REST-API, gelöschter Dienstleister), kommt durch einen späten Klick nicht zurück.
  - Ein späterer Klick prüft die Verfügbarkeit mit der vorhandenen Prüfung. Ist der Slot frei, wird der Termin neu angelegt und bestätigt, sonst erscheint eine Seite „abgelaufen“ mit Link zur Buchung.
  - Nach 24 Stunden ist der Link ungültig.
- **Aufräumen:** „lazy“ vor Verfügbarkeitsabfragen, beim Absenden und beim Laden des Backend-Kalenders, zusätzlich in `console cleanup`.
- **Limits:**
  - Der Token ist zufällig und lang, einmal verwendbar und 10 Minuten gültig.
  - „Erneut senden“ geht frühestens nach 60 Sekunden, höchstens 5 Mails pro E-Mail-Adresse und Stunde.
  - Der neue Link gilt 10 Minuten ab dem erneuten Senden, die Vormerkung hält den Slot aber höchstens 20 Minuten nach
    dem Absenden. Danach bucht ein Klick noch, solange der Slot frei ist (Regel für den späten Klick).
  - Prüfen und Zählen des Stundenlimits geschehen in einem Schritt (benannte Datenbank-Sperre je Adresse). Ein
    Sendeversuch zählt auch, wenn die Mail scheitert.
  - Die Fehlermeldungen verraten nicht, ob eine Adresse bekannt ist.
- **Umbuchung mit geänderter E-Mail:**
  - Zeit und übrige Angaben gelten sofort (0017). Die neue E-Mail kommt erst nach dem Klick in Termin-Kontaktdaten und Datensatz, unter der Belegt-Regel aus 0017.
  - Bis dahin bleiben Termin-Kontaktdaten und Datensatz bei der alten Adresse, und die Umbuchungsmail geht an sie.
  - Zweck „E-Mail-Änderung“: Ablauf löscht hier keinen Termin.
  - Hat die neue Adresse ihr Stundenlimit erreicht, gilt die Umbuchung trotzdem, die E-Mail bleibt die alte und es
    geht kein Link hinaus.
  - Abweichung (bewusst beibehalten): Eine in dieser Browser-Sitzung schon bestätigte Adresse braucht auch bei der
    Umbuchung keinen weiteren Link, wie bei der zweiten Neubuchung.
- **Backend:** Vorgemerkte Termine erscheinen im Kalender mit dem Zusatz „unbestätigt“. Bestätigen kann das Personal sie nicht, es kann sie aber wie jeden Termin löschen.
  - Abweichung (bewusst beibehalten): Das Personal kann eine Vormerkung auch nicht bearbeiten oder verschieben, nur
    löschen. Das Löschen verschickt keine Mail und löst keinen Webhook aus, weil der Termin nie bestätigt war.
  - Abweichung (bewusst beibehalten): Auch der volle Google- und CalDAV-Abgleich aus dem Backend lässt Vormerkungen
    aus, nicht nur der Sync nach einer Buchung.
- **Mail:** neue Bestätigungsmail im Stil der vorhandenen Mails, Texte über die Übersetzungen (Deutsch und Englisch, andere Sprachen fallen auf Englisch zurück).
- **Regel-Modul (Seam):** reine Funktionen für den Zustand einer Vormerkung zu einem Zeitpunkt (gültig, abgelaufen aber wiederherstellbar, verfallen) und die Entscheidung über das erneute Senden (Sperrfrist, Stundenlimit). Sie liegen im Regel-Modul aus 0017 oder in einer zweiten kleinen Fork-Library.
- **Upstream-Diff:** Eingriffe im Buchungs-Controller (Aufrufe, `verify`/`confirm_verification`, ausgelagertes
  `after_appointment_saved()`), im Kalender-Controller (Aufräumen, Markierung, Sperre beim Speichern, keine Ankündigung
  beim Löschen), in den Google- und CalDAV-Controllern (Sync-Filter), im Cleanup, in `Email_messages` (neue Mail), im
  Booking-Flow (Hinweis, „erneut senden“), in der Kalender-Anzeige (JS), in der Einstellungsseite, in der
  Einstellungsvalidierung und in den Übersetzungen. Jede Stelle ruft nur Fork-Code auf; Entscheidungen liegen in
  Fork-Klassen (`Email_verification`, `_rules`, `_session`, `_pages`, `_calendar`, `Appointment_verifications_model`).
  Die Mail-Methode liegt in `Email_messages`, weil der Mailer dort privat aufgebaut wird (ADR 0008).
- ADR 0008 (aus 0017) bekommt den Abschnitt zur Bestätigung. Verworfen wurden ein Code im Wizard (Kunden finden den Link angenehmer) und eine eigene Tabelle für Vormerkungen (sie würde Eingriffe in die Verfügbarkeitsberechnung erfordern).

## Testing Decisions

- Gute Tests prüfen beobachtbares Verhalten: Entscheidungen der reinen Funktionen und den gespeicherten Zustand nach echten HTTP-Anfragen.
- **Unit-Tests:** Zustand einer Vormerkung zu verschiedenen Zeitpunkten (vor und nach 10 Minuten, nach 24 Stunden), Sperrfrist und Stundenlimit beim erneuten Senden. Vorbild sind die Booking-Policy-Tests.
- **HTTP-Smoke:** ein Abschnitt im Smoke-Skript aus 0017, mit Einstellung 1 und gemeinsamen Fixtures:
  - `register` → vorgemerkter Termin ohne Kunde, der Slot ist in der Verfügbarkeit belegt.
  - Bestätigungslink → Termin mit Kunde, Markierung entfernt.
  - Zweiter Klick → ungültig.
  - Zurückdatierte Vormerkung → Slot frei. Später Klick bei freiem Slot → gebucht, bei belegtem Slot → abgelaufen.
  - Die Fixtures bekommen einen Befehl, der für einen Termin einen bekannten Token setzt und Zeitpunkte zurückdatiert, weil der Smoke-Test keine Mails liest.
- „Tests go red“ wie in CODING_STANDARDS.md, Beleg im Ticket.
- Wizard-Hinweis, Bestätigungsmail und die Anzeige „unbestätigt“ prüft der Nutzer manuell.

## Out of Scope

- Ein Code statt Link.
- Bestätigung bei Backend-Buchungen und über die REST-API v1.
- Eine Bestätigung der Telefonnummer (SMS).
- Ein manuelles Bestätigen durch das Personal.
- Kundenkonten mit Login.

## Further Notes

- Ohne funktionierenden Mailversand ist bei aktiver Einstellung keine Online-Buchung möglich. Das steht im Hilfetext der Einstellung und in `docs/deployment.md`.
- Herkunft: Grilling am 2026-10-11 bis 2026-10-13, siehe 0017.
- Bezug: 0006 (Slot-Kapazität), 0009 (Manage-Link), 0010 (Einstellungsregeln), 0017, ADR 0008, Glossar „Vormerkung“, „E-Mail-Bestätigung“.

## Comments

**2026-10-14 (Review-Befunde 0018 behoben):**

- Entscheidungen des Nutzers umgesetzt: R1 (Link öffnet nur die Seite mit der Schaltfläche, `POST
  booking/confirm_verification` mit CSRF bestätigt) und R2 (neuer Link 10 Minuten ab dem erneuten Senden, Slot höchstens
  20 Minuten nach dem Absenden, danach Regel für den späten Klick). Beibehaltene Abweichungen (Sync-Filter
  Google/CalDAV, Vormerkung im Backend nur löschbar, Sitzungs-Abkürzung bei der E-Mail-Änderung) oben und in ADR 0008
  dokumentiert.
- S1: Stundenlimit der neuen Adresse bei der Umbuchung lehnt nichts mehr ab (Umbuchung gilt, alte E-Mail, kein Link).
  S2: `customer_is_already_booked` läuft bei aktiver Bestätigung erst bei der Bestätigung (neutrale Seite „Termin nicht
  gebucht“, Vormerkung verworfen). S8: neue Spalte `appointment_verifications.restorable` (Migration 070), nur das
  Aufräumen nach Ablauf setzt sie; `Email_verification_rules::confirm_action()` entscheidet über Bestätigen,
  Wiederherstellen, E-Mail-Änderung oder ungültig. S10: `Appointment_verifications_model::reserve_send()` prüft und
  zählt das Limit unter `GET_LOCK` je Adresse.
- C1: `confirm()` sperrt die Markierung (`lock_by_token_hash()`, `SELECT … FOR UPDATE`) und prüft Zustand und
  Verfügbarkeit in derselben Transaktion; das Aufräumen sperrt die abgelaufenen Zeilen ebenso. C2: `forget_appointment()`
  entfällt (durch S8 unnötig), ein gescheitertes Löschen lässt die Markierung unberührt. C3: Booking und Calendar rufen
  je Stelle nur noch eine Fork-Methode auf (`link_page`, `confirm`, `check_staff_editable`, `announces_deletion`,
  `customer_of`, `hold_back_email_change`/`start_email_change` ohne Verzweigung); `after_appointment_saved()` mit
  benannten Argumenten `manage_mode:`/`notify:` aus dem Ergebnis von `confirm()`. C4: Mail bleibt in `Email_messages`
  (privater `get_php_mailer()`, keine öffentliche Methode für beliebiges HTML), Begründung in ADR 0008. C5:
  `mail_settings()` einmal (Booking nutzt es), `reserve_mail()` für alle Limitprüfungen, `enabled()` +
  Sitzungsklasse, `find_appointment()`, `pages->message()`, `customer_of()`. C6: `Email_verification` aufgeteilt in
  `Email_verification_session` (Sitzung), `Email_verification_pages` (Link-Seiten) und `Email_verification_calendar`
  (Backend/externe Kalender); dass das Model `appointments` direkt schreibt, ist im Model-Docblock begründet
  (`Appointments_model::save()` verlangt einen Kunden, die Löschungen dürfen nur kundenlose Termine treffen).
- Smoke: `email-verification-smoke.sh` jetzt 30 Checks (neu: Link öffnen ändert nichts, später Klick nach anderem
  Löschen, bekannter gebuchter Kunde, Umbuchung über dem Stundenlimit), `confirm_link()` drückt die Schaltfläche;
  Fixtures `limit-fill`, `service-attendants`, `rate-limit-reset` (der Smoke überschreitet sonst 100 Anfragen in zwei
  Minuten). Backend-Smoke Check 5 prüft jetzt, dass ein gelöschter Vormerkungs-Link nichts wiederherstellt.
- Rot vor Fix: `EmailVerificationRulesTest` – `testValidLinkConfirmsItsHeldVormerkung` u. a. (6 Tests) `Error: Undefined
  constant Email_verification_rules::CONFIRM_HELD`; `testResendNeverHoldsTheSlotLongerThanTwentyMinutesAfterTheSubmit`
  – `'2026-10-14 12:20:00'` erwartet, `'2026-10-14 12:20:01'` erhalten;
  `testLinkOfALateResendStillBooksAfterTheHoldEndsUntilTwentyFourHoursAfterTheResend` – `'restorable'` erwartet,
  `'valid'` erhalten; `testCleanupCutoffsMatchTheStateBoundaries` – `'2026-10-14 12:00:00'` erwartet, `'… 12:10:00'`.
  Smoke gegen den alten Code: `FAIL opening the link (HTTP 307): appointment {… "id_users_customer":270 …},
  verification null` (R1). Smoke mit simuliertem Altverhalten je Befund: `FAIL late click on a deleted Vormerkung: 303
  …/booking_confirmation/of/…` (S8), `FAIL booking with the email of a booked customer (HTTP 500, {… "Dieser Kunde hat
  bereits einen Termin während des angefragten Reservierungszeitraums."})` (S2), `FAIL reschedule with a limited new
  email (HTTP 500, {… "An diese Adresse wurden zu viele Bestätigungsmails geschickt. …"})` (S1). Danach alle grün.
- Nicht automatisch testbar: die Nebenläufigkeit von C1/S10 (Sperren per Review geprüft).
- Offen für den Nutzer (manuell): Seite „Termin bestätigen“ / „E-Mail-Adresse bestätigen“ nach dem Öffnen des Links
  (Deutsch/Englisch, Theme), Seite „Termin nicht gebucht“.

**2026-10-14 (Bestätigungslink in derselben Sitzung direkt bestätigen):**

- Entscheidung des Nutzers (hybrid): Öffnet die Browser-Sitzung, die die Bestätigung begonnen hat, den Link, bestätigt
  das GET direkt; jede andere Sitzung sieht unverändert die Schaltfläche und das GET ändert nichts. Die Sitzung merkt
  sich die Bestätigungs-IDs (`Email_verification_session::remember_own()` bei `start_booking()` und
  `start_email_change()`), die Regel `Email_verification_rules::confirms_on_open()` entscheidet, `open_link()` ersetzt
  `link_page()`. Implementation Decisions und ADR 0008 ergänzt.
- Smoke `email-verification-smoke.sh` jetzt 34 Checks: neuer Abschnitt 10 (anderer Cookie-Jar → Schaltfläche, Zustand
  unverändert; derselbe Cookie-Jar → direkte Weiterleitung zur Erfolgsseite mit Kunde; Link einer fremden Vormerkung
  in dieser Sitzung → Schaltfläche) und bei der E-Mail-Änderung (fremde Sitzung → Schaltfläche, Sitzung der Umbuchung
  → direkt übernommen).
- Rot vor Fix: `FAIL link in the browser session of the booking: 200 , appointment {… "id_users_customer":null …},
  verification {"id":84, …}` und `FAIL email change to a taken email: 200 , contact {… "email":"smoke-verify-change-…"}`;
  Unit `EmailVerificationRulesTest` (3 neue Tests) `Error: Call to undefined method
  Email_verification_rules::confirms_on_open()` bzw. `remember_own_verification()`. Danach alle grün.
