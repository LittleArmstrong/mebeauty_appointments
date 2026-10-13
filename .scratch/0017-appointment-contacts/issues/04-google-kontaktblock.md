# 04: Google-Termin mit Kontaktblock

**What to build:** Der Google-Termin zeigt in der Beschreibung oben einen klar abgegrenzten Kontaktblock: Name, eine Telefonnummer, die sich auf dem Handy antippen lässt, und E-Mail. Danach folgen die Notizen. Der Teilnehmer wird aus den Termin-Kontaktdaten gebildet. Liest der Sync Google-Termine zurück, wird der Block entfernt, bevor die Notizen verglichen und gespeichert werden. So gelangt er nie in die Notizen, und es entstehen keine Scheinänderungen. Termine ohne Termin-Kontaktdaten (Altbestand) nutzen den Kundendatensatz.

**Blocked by:** 02

**Status:** resolved

- [x] Regel-Modul: Kontaktblock erzeugen und aus einer Beschreibung entfernen (reine Funktionen)
- [x] Unit-Tests: Erzeugen, Entfernen, Rundlauf (Block + Notizen → Notizen), Beschreibung ohne Block bleibt unverändert, leere Notizen
- [x] Sync beim Anlegen und Aktualisieren schreibt den Block; Zurücklesen entfernt ihn
- [x] Unavailabilities (Blocker) bleiben unverändert
- [x] Manuelle Sichtprüfung in Google Kalender durch den Nutzer (Hinweis im Abschlussbericht)
- [x] `bash scripts/test.sh` grün

## Comments

- `event_description()` setzt den Block `--- Kontakt ---` / Name / `Tel: <nur Ziffern, führendes +>` / `E-Mail: …` / `---` vor die Notizen; `strip_contact_block()` entfernt ihn am Anfang, auch in der HTML-Form, die Google nach einer Bearbeitung liefert (`<br>`, `tel:`-Link). Die Texte sind bewusst feste deutsche Literale (Wiedererkennung unabhängig von der Sprache).
- `Google_sync::add_appointment/update_appointment` bilden Beschreibung und Teilnehmer aus `Appointment_contacts_model::display_customer()` (Altbestand: Datensatz). `Google.php` entfernt den Block beim Zurücklesen von Terminen; der Notizvergleich castet auf String, damit `null` vs. `''` keine Scheinänderung ist. Unavailabilities unverändert.
- Rot: neuer Test lief zuerst gegen die fehlende Library (Error). Kein Live-Test gegen Google möglich — **Sichtprüfung in Google Kalender durch den Nutzer ausstehend** (Termin anlegen, umbuchen, in Google Notiz ergänzen und Sync prüfen, Telefonnummer am Handy antippen).
- `bash scripts/test.sh` grün.
