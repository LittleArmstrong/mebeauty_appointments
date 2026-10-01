# 02: Website-Navbar

**What to build:** Die Buchungsseite zeigt oben die Website-Navbar: MeBeauty-Logo, vier Links auf die Hauptdomain (Home, Behandlungen, Preise, Kontakt) und auf dem Smartphone ein Hamburger-Menü, das sich per Vanilla-JS öffnen/schließen lässt.

**Blocked by:** 01: Theme-Fundament + Assets

**Status:** done

- [ ] Navbar mit Logo und vier Links auf `https://mebeauty-koeln.de` sichtbar über der Buchungsseite.
- [ ] Auf Desktop sichtbar (ab 1024px), auf Mobil verborgen hinter Hamburger-Button.
- [ ] Hamburger-Menü öffnet/schließt das Menü und wechselt das Icon (Burger ↔ Schließen).
- [ ] Smoke-Check: Logo-Asset, Website-Links und Mobile-Menü-Markup im gerenderten HTML vorhanden.

## Comments

Implemented on integration branch `agent/mebeauty-reskin-integration`. Commit `d1a0abee`.

Follow-ups: Navbar per Playwright-Messung 1:1 zur Website angeglichen (Menü zentriert, 20px/28px, Bar 85px) — `d03a25a0`; Referenzwerte in `docs/visual-measurement.md`.
