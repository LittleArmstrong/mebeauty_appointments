# 02: Online-Neubuchung speichert Termin-Kontaktdaten, Datensatz geschützt

**What to build:** Eine Neubuchung im Booking-Flow speichert am Termin dessen Termin-Kontaktdaten: Vorname, Nachname, E-Mail und Telefon, so wie eingegeben. Eine bekannte E-Mail ordnet den Termin weiterhin dem bestehenden Kunden zu. Den Kundendatensatz aktualisieren nur nicht leere Angaben, und zwar erst, wenn der Termin gespeichert ist. Ein neuer Kunde wird mit allen Angaben angelegt. Die Salon-Mail zeigt die Termin-Kontaktdaten. Beispiel: Lena bucht mit Annas E-Mail. Der Termin zeigt Lena. Annas frühere Termine zeigen weiterhin ihre eigenen Kontaktdaten, sofern sie welche haben. Annas Datensatz ist nur in den Feldern geändert, die Lena ausgefüllt hat.

**Blocked by:** 01

**Status:** resolved

- [x] Neue Fork-Tabelle `appointment_contacts` in Migration 070 (1:1 zum Termin, echte Spalten für die vier Felder, Zeitstempel, Fremdschlüssel mit Löschweitergabe), idempotent; Fork-Model dazu
- [x] Neues Regel-Modul (reine Funktionen, Muster Booking-Policy) entscheidet Kundenauflösung bei Neubuchung, Datensatz-Änderung (nur nicht leere Felder; Sprache/Zeitzone wie Upstream) und die Termin-Kontaktdaten
- [x] Termin, Termin-Kontaktdaten und Datensatz-Änderung in einer Transaktion, Termin zuerst; scheitert etwas, ist kein Datensatz verändert; Sync, Mails, Webhook erst danach
- [x] Ein leeres oder ausgeblendetes Formularfeld löscht keine gespeicherten Stammdaten mehr
- [x] Salon-Mail zeigt die Termin-Kontaktdaten; Kundenmail geht an die E-Mail des Datensatzes
- [x] Unit-Tests für das Regel-Modul, zuerst rot gegen das alte Verhalten (Beleg hier)
- [x] Neuer HTTP-Smoke (Muster Manage-Link-Smoke, gemeinsame Fixtures): Buchung mit fremder E-Mail → Termin-Kontaktdaten = Eingabe, Datensatz nur in nicht leeren Feldern geändert; leeres Telefon → Nummer erhalten; vorher rot belegt
- [x] `bash scripts/test.sh` grün

## Comments

- Tabelle `appointment_contacts` in 070 (Unique auf `id_appointments`, FK mit `ON DELETE CASCADE`), Model `Appointment_contacts_model`, Regel-Modul `Appointment_contact_rules` (reine Funktionen: `resolve_customer_id`, `customer_record`, `contact`, `display_customer`).
- `Booking::register`: leere Defaults für address/city/zip_code/notes/phone_number entfernt; Neukunde → Termin → Termin-Kontaktdaten → Datensatz-Update in einer Transaktion (ein neuer Kunde muss wegen des FK vor dem Termin eingefügt werden, ein bestehender Datensatz wird erst nach dem Termin geändert); Sync, Mails, Webhook nach dem Commit. Salon-Mails (Provider/Admin/Sekretär) bekommen die Termin-Kontaktdaten, die Kundenmail den Datensatz.
- Rot vor Umsetzung: `AppointmentContactRulesTest` → Error „Failed opening required …/Appointment_contact_rules.php“. Smoke `appointment-contacts` gegen das alte `Booking.php`: „FAIL booking with another person's email (HTTP 500, Not all required fields are provided …)“ (leeres Telefon wurde als Löschung gespeichert), dazu FAIL bei beiden Umbuchungsfällen und „contact of rescheduled appointment: null“. Danach 5/5 OK.
- Rollback bei Fehlern ist nicht automatisch getestet (kein Fehlerinjektionspunkt über HTTP).
- `bash scripts/test.sh` grün.
