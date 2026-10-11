# 01: Upstream-Views auf Original-Formatierung zurücksetzen

**What to build:** Alle vom Fork geänderten Upstream-Views (außer dem Nachrichten-Layout, siehe 02) weichen nur noch inhaltlich von der Upstream-Basis ab. Reine Formatierungsänderungen sind zurückgesetzt. Die Entwicklungskonventionen enthalten den Hinweis, Upstream-Views nicht automatisch zu formatieren.

**Blocked by:** None (can start immediately)

**Status:** ready-for-agent

- [ ] Liste der vom Fork geänderten Upstream-Views per Diff gegen die Basis aus `UPSTREAM.md` erstellt (im Ticket unter `## Comments`)
- [ ] Bestätigungsschritt des Booking-Flows: Captcha-Einrückung, doppeltes Leerzeichen, Leerzeile nach dem PHP-Tag, `/>`-Leerzeichen und Zeilenumbruch am Dateiende wie im Upstream; inhaltliche Änderungen (Klassen) bleiben
- [ ] Für jede bereinigte Datei enthält der Diff gegen die Basis keine reinen Whitespace-Hunks
- [ ] Hinweis in den Entwicklungskonventionen ergänzt
- [ ] Manuelle Probe der Bestätigungsseite mit Captcha; Layout-Smoke-Test, PHPUnit und `php -l` grün
