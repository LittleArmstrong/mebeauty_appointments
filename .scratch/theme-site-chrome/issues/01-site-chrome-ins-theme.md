# 01: Site-Chrome ins Theme mebeauty verschieben

**What to build:** Navbar, Seitenkopf, Footer und WhatsApp-Button liegen im Theme `mebeauty`. Das Booking-Layout bindet einen Site-Chrome-Baustein nur ein, wenn das aktive Theme ihn hat. Mit `mebeauty` sieht der Booking-Flow aus wie heute; mit einem Upstream-Theme erscheint das Upstream-Layout ohne MeBeauty-Branding.

**Blocked by:** None (can start immediately)

**Status:** ready-for-agent

- [ ] Theme-Ordner für Site-Chrome-Views nach Theme-Namen; jeder Baustein ist optional
- [ ] Die vier bestehenden Komponenten sind unverändert in den Ordner des Themes `mebeauty` verschoben; im allgemeinen Komponenten-Ordner liegen keine mehr
- [ ] Ohne Site-Chrome rendert das Layout wie Upstream (ohne Site-Seitenklasse und Seitenkopf); die Site-Styles greifen dann nicht
- [ ] Der Smoke-Test prüft zusätzlich: Mit Upstream-Theme per URL-Parameter kein MeBeauty-Site-Chrome im HTML
- [ ] Sichtprüfung mit `mebeauty` (unverändert) und `default` (Upstream-Optik)
- [ ] Layout-Smoke-Test, PHPUnit und `php -l` grün
