# 02: Online-Neubuchung speichert Termin-Kontaktdaten, Datensatz geschützt

**What to build:** Eine Neubuchung im Booking-Flow speichert am Termin dessen Termin-Kontaktdaten: Vorname, Nachname, E-Mail und Telefon, so wie eingegeben. Eine bekannte E-Mail ordnet den Termin weiterhin dem bestehenden Kunden zu. Den Kundendatensatz aktualisieren nur nicht leere Angaben, und zwar erst, wenn der Termin gespeichert ist. Ein neuer Kunde wird mit allen Angaben angelegt. Die Salon-Mail zeigt die Termin-Kontaktdaten. Beispiel: Lena bucht mit Annas E-Mail. Der Termin zeigt Lena. Annas frühere Termine zeigen weiterhin ihre eigenen Kontaktdaten, sofern sie welche haben. Annas Datensatz ist nur in den Feldern geändert, die Lena ausgefüllt hat.

**Blocked by:** 01

**Status:** ready-for-agent

- [ ] Neue Fork-Tabelle `appointment_contacts` in Migration 070 (1:1 zum Termin, echte Spalten für die vier Felder, Zeitstempel, Fremdschlüssel mit Löschweitergabe), idempotent; Fork-Model dazu
- [ ] Neues Regel-Modul (reine Funktionen, Muster Booking-Policy) entscheidet Kundenauflösung bei Neubuchung, Datensatz-Änderung (nur nicht leere Felder; Sprache/Zeitzone wie Upstream) und die Termin-Kontaktdaten
- [ ] Termin, Termin-Kontaktdaten und Datensatz-Änderung in einer Transaktion, Termin zuerst; scheitert etwas, ist kein Datensatz verändert; Sync, Mails, Webhook erst danach
- [ ] Ein leeres oder ausgeblendetes Formularfeld löscht keine gespeicherten Stammdaten mehr
- [ ] Salon-Mail zeigt die Termin-Kontaktdaten; Kundenmail geht an die E-Mail des Datensatzes
- [ ] Unit-Tests für das Regel-Modul, zuerst rot gegen das alte Verhalten (Beleg hier)
- [ ] Neuer HTTP-Smoke (Muster Manage-Link-Smoke, gemeinsame Fixtures): Buchung mit fremder E-Mail → Termin-Kontaktdaten = Eingabe, Datensatz nur in nicht leeren Feldern geändert; leeres Telefon → Nummer erhalten; vorher rot belegt
- [ ] `bash scripts/test.sh` grün
