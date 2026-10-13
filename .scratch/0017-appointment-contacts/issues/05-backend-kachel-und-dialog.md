# 05: Backend: Kachel und Termin-Dialog

**What to build:** Im Backend-Kalender zeigt die Kachel den Namen aus den Termin-Kontaktdaten. Der Termin-Dialog hat einen Kundenblock, vorbelegt mit den Termin-Kontaktdaten. Legt das Personal einen Termin an, entstehen Termin-Kontaktdaten aus dem Dialog. Beim Bearbeiten werden die Termin-Kontaktdaten nur geändert, wenn das Personal Kontaktfelder ändert, und die geänderten Felder übernimmt auch der Kundendatensatz. Ein reines Verschieben ändert nichts an Kontaktdaten oder Datensatz. Kundenauswahl und die Pflege weiterer Stammdaten bleiben, wie sie sind. Termine ohne Termin-Kontaktdaten zeigen den Datensatz.

**Blocked by:** 02

**Status:** ready-for-agent

- [ ] Kachel und Dialog zeigen die Termin-Kontaktdaten, Rückfall auf den Datensatz bei Altbestand
- [ ] Anlegen im Backend speichert Termin-Kontaktdaten
- [ ] Regel-Modul: geänderte Felder gegenüber den gespeicherten Termin-Kontaktdaten; Unit-Tests dafür
- [ ] Bearbeiten: nur geänderte Kontaktfelder wirken auf Termin-Kontaktdaten und Datensatz; Verschieben lässt beide unverändert
- [ ] Berechtigungsprüfungen des Backends bleiben unverändert
- [ ] Manuelle Sichtprüfung durch den Nutzer (Kachel, Dialog, Anlegen, Bearbeiten, Verschieben)
- [ ] `bash scripts/test.sh` grün
