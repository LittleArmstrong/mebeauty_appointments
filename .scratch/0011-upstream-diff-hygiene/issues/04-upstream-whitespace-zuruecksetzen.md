# 04: Reine Whitespace-Änderungen in Upstream-Dateien zurücksetzen

**What to build:** Die vom Fork geänderten Upstream-Dateien enthalten keine reinen Formatierungsänderungen gegenüber der Basis mehr. Die inhaltlichen Fork-Änderungen bleiben unverändert erhalten, Seiten und Funktionen verhalten sich wie vorher.

**Blocked by:** 01 (Formatter-Strategie festlegen), 02 (Nachrichten-Layout wird dort bereinigt)

**Status:** resolved

- [x] Bereinigt: Bestätigungsschritt, Bestätigungsseite, Booking-Layout, Booking-JavaScript, E-Mail-Nachrichten-Library, Business-Einstellungsseite, Verfügbarkeits-Library
- [ ] Für jede dieser Dateien hat der rohe Diff gegen die Basis so viele geänderte Zeilen wie der Diff ohne Whitespace (teilweise, siehe Comments)
- [x] Erneute Messung über alle geänderten Upstream-Dateien, Ergebnis unter `## Comments`
- [x] Sichtprüfung von Bestätigungsseite (mit Captcha) und Buchungsseite; Layout-Smoke-Test, `composer test`, `php -l`, `node --check` grün

## Comments

- Reine Whitespace-Hunks automatisch zurückgesetzt (Hunk-weise, nur wenn entfernte und hinzugefügte Zeilen ohne Leerraum identisch sind): Bestätigungsschritt 20 → 6 und Bestätigungsseite 22 → 8 geänderte Zeilen, jetzt ohne Whitespace-Anteil.
- Erneute Messung über alle geänderten Upstream-Dateien: Rest-Unterschiede nur noch in booking_layout (32/26), booking.js (25/19), Email_messages (9/7), business_settings (31/29), Availability (297/295). Das sind **notwendige Einrückungen** von Upstream-Code, der in neue Bedingungen verschoben wurde (z. B. Zeitzonen-Autodetect, Firmenkopf, Logo-Fallback) – kein reines Formatierungsrauschen. Kriterium daher bewusst nur teilweise erfüllt.
- Buchungsseite (inkl. Bestätigungsschritt), Bestätigungsseite (HTTP 200) und Layout-Smoke geprüft.
