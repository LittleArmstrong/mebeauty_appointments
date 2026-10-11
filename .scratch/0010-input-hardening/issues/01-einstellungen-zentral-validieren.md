# 01: Fork-Einstellungen und Firmenfarbe zentral validieren, atomar speichern

**What to build:** Ungültige Werte für Buchungsfrist, Manage-Frist, Pufferzeit, Zeitzonen-Anzeige, Firmenkopf und Firmenfarbe werden abgelehnt, egal ob über die Backend-Seiten oder die Einstellungs-API. Speichert ein Admin die Buchungs- oder Business-Einstellungen mit einem ungültigen Wert, wird nichts gespeichert, und die Meldung nennt das Feld.

**Blocked by:** None (can start immediately)

**Status:** ready-for-agent

- [ ] Reine Regel-Funktion (Name + Wert → ok/Fehler) mit den Regeln aus der Spec; die Validierung des Settings-Models ruft sie für jede gespeicherte Einstellung auf
- [ ] Die Speicher-Aktionen der Buchungs- und Business-Einstellungen validieren alle übermittelten Werte vor dem ersten Speichern; bei einem Fehler wird nichts gespeichert
- [ ] Die Einstellungs-API lehnt ungültige Werte mit Fehlerantwort ab (manuelle Probe per API-Token)
- [ ] Unit-Tests: Grenzwerte gültig (`00:00`, `23:59`, `0`, `#fff`, `#d1ae5e`, `#ffffff`); ungültig mit Feldbezug (`24:00`, `9:5`, `-1`, `abc`, unbekannter Modus, `red;}`); alle Defaults der Migration 070 gültig
- [ ] Upstream-Einstellungen (außer Firmenfarbe) bleiben ungeprüft
- [ ] `composer test` und `php -l` grün
