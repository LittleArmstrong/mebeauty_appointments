# 05: Backend: Kachel und Termin-Dialog

**What to build:** Im Backend-Kalender zeigt die Kachel den Namen aus den Termin-Kontaktdaten. Der Termin-Dialog hat einen Kundenblock, vorbelegt mit den Termin-Kontaktdaten. Legt das Personal einen Termin an, entstehen Termin-Kontaktdaten aus dem Dialog. Beim Bearbeiten werden die Termin-Kontaktdaten nur geändert, wenn das Personal Kontaktfelder ändert, und die geänderten Felder übernimmt auch der Kundendatensatz. Ein reines Verschieben ändert nichts an Kontaktdaten oder Datensatz. Kundenauswahl und die Pflege weiterer Stammdaten bleiben, wie sie sind. Termine ohne Termin-Kontaktdaten zeigen den Datensatz.

**Blocked by:** 02

**Status:** needs-verification

- [x] Kachel und Dialog zeigen die Termin-Kontaktdaten, Rückfall auf den Datensatz bei Altbestand
- [x] Anlegen im Backend speichert Termin-Kontaktdaten
- [x] Regel-Modul: geänderte Felder gegenüber den gespeicherten Termin-Kontaktdaten; Unit-Tests dafür
- [x] Bearbeiten: nur geänderte Kontaktfelder wirken auf Termin-Kontaktdaten und Datensatz; Verschieben lässt beide unverändert
- [x] Berechtigungsprüfungen des Backends bleiben unverändert
- [ ] Manuelle Sichtprüfung durch den Nutzer (Kachel, Dialog, Anlegen, Bearbeiten, Verschieben)
- [x] `bash scripts/test.sh` grün

## Comments

- Kachel, Tabellenansicht und Dialog: `apply_to_customers()` legt die Termin-Kontaktdaten über `appointment.customer` (Kalender-JS unverändert); `calendar/reschedule/<hash>` ebenso. Rückfall auf den Datensatz ohne Kontaktzeile.
- `Appointment_contact_rules::backend_save()` entscheidet: Bearbeiten des Kunden des Termins → nur gegenüber den Termin-Kontaktdaten (Altbestand: Datensatz) geänderte Felder gehen in Kontakt und Datensatz; neuer Termin oder anderer Kunde → Dialogeingabe wird Kontakt. Verschieben (Drag & Drop sendet keine Kundendaten) ändert nichts. Berechtigungsprüfungen unverändert.
- Per HTTP mit Admin-Login geprüft (Wegwerfskript): Telefon geändert → Kontakt + Datensatz 0999; nur Nachname geändert, Datensatz-Vorname vorher extern auf „Anders“ → Datensatz behält „Anders“, Kontakt „Smoke“; Verschieben → Kontakt unverändert; Neuanlage → Kontakt aus Dialog; Liste zeigt Kontaktdaten, Altbestand den Datensatz.
- Rot vor Fix: `AppointmentContactRulesTest` (Backend-Diff `changed_fields`) – Error „Failed opening required …/Appointment_contact_rules.php“. Die `backend_save`-Tests kamen im Review-Refactor dazu (Logik aus `Calendar.php` verschoben) und waren sofort grün; das Verhalten belegen zusätzlich die HTTP-Prüfungen oben.
- Bekannter Grenzfall: Wählt das Personal im Dialog denselben Kunden erneut aus der Liste, füllt das die Felder mit dem Datensatz; weichen diese vom Kontakt ab, zählt das als Änderung.
- **Manuelle Sichtprüfung durch den Nutzer ausstehend** (Kachel, Dialog, Anlegen, Bearbeiten, Verschieben).
- `bash scripts/test.sh` grün.
