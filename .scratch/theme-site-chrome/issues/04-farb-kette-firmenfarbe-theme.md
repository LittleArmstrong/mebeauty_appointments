# 04: Theme-Standardfarbe und einheitliche Farb-Kette für Browserleiste und E-Mails

**What to build:** Ein Theme deklariert seine Standardfarbe serverseitig lesbar (MeBeauty: Gold). Browserleiste (Booking-Flow) und E-Mails verwenden dann: gültige Firmenfarbe → Standardfarbe des aktiven Themes → Upstream-Grün. Ohne Firmenfarbe zeigt MeBeauty damit weiter Gold, ein anderes Kunden-Theme seine eigene Farbe und ein Upstream-Theme Grün.

**Blocked by:** 01 (Site-Chrome ins Theme mebeauty verschieben), input-hardening/01 (Farbformat validiert)

**Status:** ready-for-agent

- [ ] Das Theme `mebeauty` deklariert seine Standardfarbe `#d1ae5e` (und ggf. die Hover-Farbe) in seinem View-Ordner, mit Hinweis auf die gleichwertige SCSS-Definition
- [ ] Ein gemeinsamer Baustein bestimmt die Hauptfarbe nach der Kette; Weiß gilt als „keine Firmenfarbe“
- [ ] Booking-Layout (Browserleiste) und alle E-Mail-Vorlagen nutzen den Baustein; die globalen Gold-Konstanten werden nicht mehr verwendet
- [ ] Smoke bzw. manuelle Probe: Theme `mebeauty` ohne Firmenfarbe → Gold in Browserleiste und E-Mail; `?theme=default` ohne Firmenfarbe → `#35A768`; gesetzte Firmenfarbe → Firmenfarbe
- [ ] Layout-Smoke-Test, E-Mail-Logo-Smoke-Test, `composer test`, `php -l` grün
