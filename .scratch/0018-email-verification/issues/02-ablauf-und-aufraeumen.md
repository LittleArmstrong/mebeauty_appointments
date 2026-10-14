# 02: Ablauf, Wiederherstellung und Aufräumen

**What to build:** Eine Vormerkung, die nach 10 Minuten noch nicht bestätigt ist, gibt ihren Slot frei, und ein Cronjob ist dafür nicht nötig. Klickt die Kundin den Link später (bis 24 Stunden), wird gebucht, sofern der Slot noch frei ist. Ist er vergeben, erscheint eine Seite „Leider abgelaufen“ mit Link zur Buchung. Nach 24 Stunden ist der Link ungültig, und die Wiederherstellungsdaten sind gelöscht.

**Blocked by:** 01

**Status:** needs-verification

- [x] Regel-Modul: Zustand einer Vormerkung zu einem Zeitpunkt (gültig / abgelaufen aber wiederherstellbar / verfallen); Unit-Tests an den Grenzen (10 Minuten, 24 Stunden)
- [x] Lazy Cleanup vor Verfügbarkeitsabfragen, beim Absenden und beim Laden des Backend-Kalenders löscht abgelaufene Vormerkungs-Termine und verfallene Wiederherstellungsdaten
- [x] `console cleanup` macht dasselbe
- [x] Später Klick: Slot frei → Termin neu angelegt und bestätigt (vorhandene Verfügbarkeitsprüfung); Slot belegt → Seite „abgelaufen“
- [x] Zweck „E-Mail-Änderung“ löscht beim Ablauf keinen Termin
- [x] Smoke mit zurückdatierter Vormerkung (Fixture): Slot frei nach Ablauf; später Klick bei freiem bzw. belegtem Slot
- [x] `bash scripts/test.sh` grün

## Comments

- Regel-Modul `Email_verification_rules`: `state(verification, now)` → `valid` (bis `expires_datetime`, 10 Minuten nach der Mail) / `restorable` (bis 24 Stunden nach der Mail) / `void`; `cleanup_cutoffs(now)` liefert die passenden SQL-Grenzen, `purposes_freeing_slot()` / `expiry_frees_slot()` (nur `booking`; `email_change` aus 05 behält beim Ablauf seinen Termin), `restored_appointment()` baut den Termin aus den Wiederherstellungsdaten (Farbe/Ort vom Service, erster Status). Die 24 Stunden zählen ab der letzten Mail (`expires_datetime − 10 min`), damit 03 („erneut senden“) das Fenster mitverschiebt.
- Aufräumen: `Email_verification::cleanup_expired()` → `Appointment_verifications_model::delete_expired()` löscht abgelaufene Vormerkungs-Termine (nur ohne Kunde; Bestätigung bleibt dank `ON DELETE SET NULL`) und verfallene Bestätigungen. Aufgerufen in `Booking::get_available_hours`, `get_unavailable_dates`, `register` (vor der Verfügbarkeitsprüfung), `Calendar::get_calendar_appointments(_for_table_view)`, in `confirm()` und über `Cleanup::cleanup_email_verifications()` in `console cleanup`.
- Später Klick: `confirm()` bekommt die vorhandene Prüfung `Booking::check_datetime_availability` als Callback. Slot frei → Termin neu angelegt (mit Termin-Kontaktdaten), Kunde zugeordnet, Weiterleitung auf die Erfolgsseite; Slot belegt → Seite „Leider abgelaufen“ mit Link zur Buchung (`email_verification_expired_title/_text`, de/en), Wiederherstellungsdaten bleiben bis 24 Stunden. Verfallen → neutrale Seite „Link ungültig“.
- Fixtures: `verification-token` nimmt auch eine E-Mail (Termin nach Ablauf gelöscht), neu `appointment-by-hash` und `cleanup-verifications` (Cleanup-Teil von `console cleanup`).
- Rot vor Fix: `EmailVerificationRulesTest` – 8 Errors („Undefined constant Email_verification_rules::STATE_VALID“, „Call to undefined method …::cleanup_cutoffs()/expiry_frees_slot()/restored_appointment()“).
- Rot vor Fix: Smoke `email-verification` (neue Abschnitte gegen den Code aus 01) – „FAIL after expiry: slots [… ohne 09:00 …], appointment {…"id_users_customer":null…}“, „FAIL booking the slot of an expired Vormerkung (HTTP 500, … nicht verfügbar …)“, „FAIL late click with a taken slot: 307 …/booking_confirmation/of/…“ (alter Code bestätigte die abgelaufene Vormerkung). Danach 13/13 OK.
- `console cleanup` selbst nicht im Smoke ausgeführt (räumt auch Sessions/Logs/Cache der Dev-Umgebung); geprüft wird dieselbe Methode über die Fixture.
- Manuelle Sichtprüfung offen: Seite „Leider abgelaufen“ (Deutsch/Englisch).
- `bash scripts/test.sh` grün.
