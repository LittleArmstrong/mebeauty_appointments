# 06: Backend-Header schwarz unter mebeauty-Theme

**What to build:** Unter dem `mebeauty`-Theme ist der Backend-Header schwarz (reines `#000`), unabhängig von der Firmenfarbe. Die Vorrang-Kette ist: Theme-explizit → Firmenfarbe → Theme-Primärfarbe. Dafür wird eine CSS-Variable mit Fallback verwendet; die Firmenfarbe färbt den Header nicht mehr.

**Blocked by:** None (can start immediately).

**Status:** ready-for-agent

- [ ] Das `mebeauty`-Theme setzt eine explizite Header-Farbe (reines Schwarz)
- [ ] Die Header-Hintergrundfarbe folgt `var(--ea-header-bg, var(--bs-primary))` (Theme-explizit → Firmenfarbe → Theme-Primärfarbe)
- [ ] Die Firmenfarbe (`company_color_style`) überschreibt den Header nicht mehr, so dass Theme-Vorrang gilt
- [ ] Unter anderen Themes folgt der Header weiterhin Firmenfarbe bzw. Theme-Primärfarbe
- [ ] CSS wird via `npx gulp styles` neu gebaut
