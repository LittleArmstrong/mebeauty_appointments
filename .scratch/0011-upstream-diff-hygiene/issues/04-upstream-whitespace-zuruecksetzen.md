# 04: Reine Whitespace-Änderungen in Upstream-Dateien zurücksetzen

**What to build:** Die vom Fork geänderten Upstream-Dateien enthalten keine reinen Formatierungsänderungen gegenüber der Basis mehr. Die inhaltlichen Fork-Änderungen bleiben unverändert erhalten, Seiten und Funktionen verhalten sich wie vorher.

**Blocked by:** 01 (Formatter-Strategie festlegen), 02 (Nachrichten-Layout wird dort bereinigt)

**Status:** ready-for-agent

- [ ] Bereinigt: Bestätigungsschritt, Bestätigungsseite, Booking-Layout, Booking-JavaScript, E-Mail-Nachrichten-Library, Business-Einstellungsseite, Verfügbarkeits-Library
- [ ] Für jede dieser Dateien hat der rohe Diff gegen die Basis so viele geänderte Zeilen wie der Diff ohne Whitespace
- [ ] Erneute Messung über alle geänderten Upstream-Dateien, Ergebnis unter `## Comments`
- [ ] Sichtprüfung von Bestätigungsseite (mit Captcha) und Buchungsseite; Layout-Smoke-Test, `composer test`, `php -l`, `node --check` grün
