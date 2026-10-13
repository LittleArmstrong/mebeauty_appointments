# 04: Google-Termin mit Kontaktblock

**What to build:** Der Google-Termin zeigt in der Beschreibung oben einen klar abgegrenzten Kontaktblock: Name, eine Telefonnummer, die sich auf dem Handy antippen lässt, und E-Mail. Danach folgen die Notizen. Der Teilnehmer wird aus den Termin-Kontaktdaten gebildet. Liest der Sync Google-Termine zurück, wird der Block entfernt, bevor die Notizen verglichen und gespeichert werden. So gelangt er nie in die Notizen, und es entstehen keine Scheinänderungen. Termine ohne Termin-Kontaktdaten (Altbestand) nutzen den Kundendatensatz.

**Blocked by:** 02

**Status:** ready-for-agent

- [ ] Regel-Modul: Kontaktblock erzeugen und aus einer Beschreibung entfernen (reine Funktionen)
- [ ] Unit-Tests: Erzeugen, Entfernen, Rundlauf (Block + Notizen → Notizen), Beschreibung ohne Block bleibt unverändert, leere Notizen
- [ ] Sync beim Anlegen und Aktualisieren schreibt den Block; Zurücklesen entfernt ihn
- [ ] Unavailabilities (Blocker) bleiben unverändert
- [ ] Manuelle Sichtprüfung in Google Kalender durch den Nutzer (Hinweis im Abschlussbericht)
- [ ] `bash scripts/test.sh` grün
