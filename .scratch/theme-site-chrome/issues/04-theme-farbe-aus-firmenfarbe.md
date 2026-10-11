# 04: Browser-Theme-Farbe aus der Firmenfarbe

**What to build:** Die Browser-Leiste auf Mobilgeräten zeigt im Booking-Flow die Firmenfarbe. Ist keine Firmenfarbe gesetzt, gilt die Standard-Primärfarbe statt eines fest codierten Werts.

**Blocked by:** 01 (Site-Chrome ins Theme mebeauty verschieben)

**Status:** ready-for-agent

- [ ] Das Theme-Farb-Meta-Tag im Booking-Layout nutzt die Firmenfarbe, sonst die Standard-Primärfarbe
- [ ] Der Wert ist escaped
- [ ] Layout-Smoke-Test, PHPUnit und `php -l` grün
