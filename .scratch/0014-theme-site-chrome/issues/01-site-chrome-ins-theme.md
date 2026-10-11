# 01: Site-Chrome ins Theme mebeauty verschieben

**What to build:** Navbar, Seitenkopf, Footer und WhatsApp-Button liegen im Theme `mebeauty`. Das Booking-Layout bindet einen Site-Chrome-Baustein nur ein, wenn das aktive Theme ihn hat. Mit `mebeauty` sieht der Booking-Flow aus wie heute; mit einem Upstream-Theme erscheint das Upstream-Layout ohne MeBeauty-Branding.

**Blocked by:** None (can start immediately)

**Status:** resolved

- [x] Theme-Ordner für Site-Chrome-Views nach Theme-Namen; jeder Baustein ist optional
- [x] Die vier bestehenden Komponenten sind unverändert in den Ordner des Themes `mebeauty` verschoben; im allgemeinen Komponenten-Ordner liegen keine mehr
- [x] Ohne Site-Chrome rendert das Layout wie Upstream (ohne Site-Seitenklasse und Seitenkopf); die Site-Styles greifen dann nicht
- [x] Der Smoke-Test prüft zusätzlich: Mit Upstream-Theme per URL-Parameter kein MeBeauty-Site-Chrome im HTML
- [x] `CODING_STANDARDS.md` (Ausnahme für die Website-Komponenten) und `docs/assets.md` nennen den neuen Ablageort im Theme
- [x] Sichtprüfung mit `mebeauty` (unverändert) und `default` (Upstream-Optik)
- [x] Layout-Smoke-Test, PHPUnit und `php -l` grün

## Comments

- Bausteine nach `application/views/themes/mebeauty/` verschoben; neue Helper `theme_view()`/`theme_component()` (Theme aus der bereinigten View-Variable `theme`). Ohne Chrome nutzt das Booking-Layout die Upstream-Container-Klassen (zentrierter Wizard); die Site-Styles hängen an eigenen `site-*`-Klassen und greifen dann nicht.
- Layout-Smoke um 5 Prüfungen mit `?theme=default` erweitert (21/21 grün). Sichtprüfung per Playwright (Docker-Image laut docs/visual-measurement.md): `mebeauty` unverändert, `default` in Upstream-Optik. CODING_STANDARDS.md und docs/assets.md nennen den neuen Ablageort.
