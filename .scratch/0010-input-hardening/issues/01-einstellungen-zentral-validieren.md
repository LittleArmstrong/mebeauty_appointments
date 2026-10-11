# 01: Fork-Einstellungen und Firmenfarbe zentral validieren, atomar speichern

**What to build:** Ungültige Werte für Buchungsfrist, Manage-Frist, Pufferzeit, Zeitzonen-Anzeige, Firmenkopf und Firmenfarbe werden abgelehnt, egal ob über die Backend-Seiten oder die Einstellungs-API. Speichert ein Admin die Buchungs- oder Business-Einstellungen mit einem ungültigen Wert, wird nichts gespeichert, und die Meldung nennt das Feld.

**Blocked by:** None (can start immediately)

**Status:** resolved

- [x] Reine Regel-Funktion (Name + Wert → ok/Fehler) mit den Regeln aus der Spec; die Validierung des Settings-Models ruft sie für jede gespeicherte Einstellung auf
- [x] Die Speicher-Aktionen der Buchungs- und Business-Einstellungen validieren alle übermittelten Werte vor dem ersten Speichern; bei einem Fehler wird nichts gespeichert
- [x] Die Einstellungs-API lehnt ungültige Werte mit Fehlerantwort ab (manuelle Probe per API-Token)
- [x] Unit-Tests: Grenzwerte gültig (`00:00`, `23:59`, `0`, `#fff`, `#d1ae5e`, `#ffffff`); ungültig mit Feldbezug (`24:00`, `9:5`, `-1`, `abc`, unbekannter Modus, `red;}`); alle Defaults der Migration 070 gültig
- [x] Upstream-Einstellungen (außer Firmenfarbe) bleiben ungeprüft
- [x] `composer test` und `php -l` grün

## Comments

- Regel-Funktion `validate_setting_value()` im (autogeladenen) Validierungs-Helper; `Settings_model::validate()` prüft jede Einstellung, `Settings_model::validate_values()` prüft vorab alle Werte in den Speicher-Aktionen der Buchungs-, Business- und allgemeinen Einstellungen (Firmenfarbe liegt in den allgemeinen Einstellungen).
- Settings-API geprüft: `25:99` → Fehler mit Feldbezug, `17:30` → gespeichert (Token und Wert danach zurückgesetzt). Unit-Tests inkl. Migrations-Defaults und Batch-Ablehnung.

- Review: Zeitformat- und Farbregel mit `/D` verschärft (abschließender Zeilenumbruch wurde akzeptiert), Prüfreihenfolge in den allgemeinen Einstellungen korrigiert (erst Array-Prüfung). Leere Werte bleiben bewusst ungültig (Fehlermeldung nennt das Feld).
