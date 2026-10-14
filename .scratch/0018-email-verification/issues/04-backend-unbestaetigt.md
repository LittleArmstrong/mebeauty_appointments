# 04: Backend zeigt „unbestätigt“

**What to build:** Im Backend-Kalender erscheinen Vormerkungen mit dem Zusatz „unbestätigt“ und dem Namen aus den Termin-Kontaktdaten. So vergibt das Personal den Slot nicht telefonisch doppelt. Das Personal kann eine Vormerkung löschen, bestätigen kann es sie nicht. Abgelaufene Vormerkungen verschwinden durch das Aufräumen. In Google erscheint eine Vormerkung erst nach der Bestätigung.

**Blocked by:** 01, 0017/05

**Status:** needs-verification

- [x] Kachel und Dialog kennzeichnen Vormerkungen als „unbestätigt“ (Texte Deutsch/Englisch)
- [x] Löschen einer Vormerkung entfernt auch ihre Bestätigungsdaten
- [x] Eine Vormerkung ohne Kunde bricht weder Kalender noch Dialog
- [x] Kein Google-Sync für Vormerkungen
- [ ] Manuelle Sichtprüfung durch den Nutzer
- [x] `bash scripts/test.sh` grün

## Comments

**2026-10-14 (0018/04 umgesetzt):**

- Regel-Modul `Email_verification_rules::unconfirmed_appointment_ids()`: nur Bestätigungen mit Zweck `booking` markieren
  ihren Termin als Vormerkung; `email_change` an einem bestätigten Termin nicht.
- `Email_verification`: `mark_unconfirmed()` (setzt `is_unconfirmed` an geladenen Terminen), `is_unconfirmed()`,
  `without_unconfirmed()` (für externe Kalender), `forget_appointment()` (Model `delete_by_appointment()`).
- Kalender (`get_calendar_appointments`, Tabellenansicht) und `calendar/reschedule/<hash>`: Termin ohne Kunde lädt
  keinen Datensatz mehr (vorher TypeError in `Customers_model::find(null)`), zeigt die Termin-Kontaktdaten
  (`Appointment_contacts_model::apply_to_customers/display_customer` nehmen dafür auch einen leeren Kunden) und trägt
  `is_unconfirmed`. JS: Kachel-Titel „(unbestätigt) Name - Leistung“, Kachel nicht verschiebbar, Popover-Status
  „unbestätigt“ ohne Bearbeiten-Knopf (Löschen bleibt), Dialogtitel „(unbestätigt) Termin bearbeiten“. Texte
  `unconfirmed`, `appointment_unconfirmed_not_editable` (de/en). Min-JS mit `npx gulp scripts` neu gebaut.
- `save_appointment` lehnt eine Vormerkung mit `appointment_unconfirmed_not_editable` ab (Personal kann nicht
  bestätigen oder verschieben). `delete_appointment` löscht vorher alle Bestätigungsdaten des Termins (sonst blieben
  sie per `ON DELETE SET NULL` als Wiederherstellungsdaten und ein später Klick könnte den Termin neu anlegen);
  für eine Vormerkung keine Lösch-Mails und kein Webhook (sie wurde nie bestätigt).
- Kein externer Sync: `Google::sync` und `Caldav::sync` filtern Vormerkungen mit `without_unconfirmed()` aus (vorher
  brach die Schleife an `Customers_model::find(null)` ab). Online-Buchung (01) und Backend-Speichern synchronisieren
  Vormerkungen ohnehin nicht. Ohne Google-Konto nicht per Smoke prüfbar; belegt durch Code und den Filter-Unit-Test.
- Neuer Smoke `tests/smoke/email-verification-backend-smoke.sh` (5 Checks) mit Fixtures `vormerkung` und
  `email-change-verification`.
- Rot vor Fix: `EmailVerificationRulesTest::testOnlyABookingVerificationMarksItsAppointmentAsUnconfirmed` – `Error: Call
  to undefined method Email_verification_rules::unconfirmed_appointment_ids()`; Smoke gegen den alten Code: alle 5 FAIL
  – Kalender/Tabelle `Customers_model::find(): Argument #1 ($customer_id) must be of type int, null given`,
  Reschedule-Seite HTTP 500, Speichern `Not all required fields are provided` (statt Hinweis „unbestätigt“), Löschen
  ebenfalls TypeError und Bestätigung blieb bestehen.
- `bash scripts/test.sh` grün.
- Abweichung/Hinweis: API v1 (`appointments` mit `with=customer`) und andere Stellen, die Termine samt Kunde laden, sind
  nicht Teil dieses Tickets; Vormerkungen erscheinen dort weiterhin ohne Kunde.
- **Manuelle Sichtprüfung durch den Nutzer ausstehend**: Kachel (Kalender und Tabellenansicht), Popover, Dialog über
  `calendar/reschedule/<hash>`, Löschen einer Vormerkung.
