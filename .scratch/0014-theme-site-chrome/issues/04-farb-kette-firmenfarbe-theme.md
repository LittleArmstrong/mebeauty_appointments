# 04: Theme-Standardfarbe und einheitliche Farb-Kette für Browserleiste und E-Mails

**What to build:** Ein Theme deklariert seine Standardfarbe serverseitig lesbar (MeBeauty: Gold). Browserleiste (Booking-Flow) und E-Mails verwenden dann: gültige Firmenfarbe → Standardfarbe des aktiven Themes → Upstream-Grün. Ohne Firmenfarbe zeigt MeBeauty damit weiter Gold, ein anderes Kunden-Theme seine eigene Farbe und ein Upstream-Theme Grün.

**Blocked by:** 01 (Site-Chrome ins Theme mebeauty verschieben), 0010/01 (Farbformat validiert)

**Status:** resolved

- [x] Das Theme `mebeauty` deklariert seine Standardfarbe `#d1ae5e` (und ggf. die Hover-Farbe) in seinem View-Ordner, mit Hinweis auf die gleichwertige SCSS-Definition
- [x] Ein gemeinsamer Baustein bestimmt die Hauptfarbe nach der Kette; Weiß gilt als „keine Firmenfarbe“
- [x] Booking-Layout (Browserleiste) und alle E-Mail-Vorlagen nutzen den Baustein; die globalen Gold-Konstanten werden nicht mehr verwendet
- [x] Smoke bzw. manuelle Probe: Theme `mebeauty` ohne Firmenfarbe → Gold in Browserleiste und E-Mail; `?theme=default` ohne Firmenfarbe → `#35A768`; gesetzte Firmenfarbe → Firmenfarbe
- [x] Layout-Smoke-Test, E-Mail-Logo-Smoke-Test, `composer test`, `php -l` grün

## Comments

- `application/views/themes/mebeauty/theme.php` deklariert `primary_color`/`primary_color_hover`; Helper `theme_config()` und `brand_colors()` (Firmenfarbe → Theme-Standardfarbe → Upstream-Farbe). Upstream-Rückfall je Verwendung exakt wie bisher upstream: Browserleiste `#35A768`, E-Mails `#429A82`/Hover `#34495e`. Gold-Konstanten `DEFAULT_PRIMARY_COLOR(_HOVER)` entfernt.
- Geprüft: Browserleiste mebeauty `#d1ae5e`, `?theme=default` `#35A768`, mit Firmenfarbe `#123456`; Passwort-E-Mail (Mailpit) mit Firmenfarbe `#123456`, ohne Gold + Hover `#b8943f`. Unit-Tests für die Kette.
