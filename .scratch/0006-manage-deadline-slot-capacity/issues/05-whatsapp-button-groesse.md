# 05: WhatsApp-Button Größe

**What to build:** Der schwebende WhatsApp-Button auf der Buchungsseite ist nach der Anpassung genauso groß wie auf der Hauptseite. Die tatsächliche gerenderte Größe wird per Playwright gemessen (Buchungsseite vs. Hauptseite, mobile/desktop); bei Abweichung wird das CSS angepasst und der Build neu erzeugt.

**Blocked by:** None (can start immediately)

**Status:** resolved

- [x] Gerenderte Größe des Buttons (Buchungsseite vs. Hauptseite) bei mobile und desktop per Playwright gemessen und dokumentiert
- [ ] Bei Abweichung `frontend.scss` angepasst (Button/SVG-Größe an Hauptseite angeglichen)
- [ ] `npm run build` erzeugt `frontend.min.css` neu
- [x] Smoke-Check `tests/smoke/mebeauty-layout-smoke.sh` bleibt grün (falls Umgebung läuft)

## Comments

Playwright-Messung (Chromium 1.63, Breakpoints 375px und 1280px, `a[aria-label*="WhatsApp"]`):

| Ziel | 375px Button | 375px SVG | 1280px Button | 1280px SVG |
|---|---|---|---|---|
| Buchungsseite (`/index.php/booking`) | 48×48 | 24×24 | 64×64 | 40×40 |
| Hauptseite (mebeauty-koeln.de) | 48×48 | 24×24 | 64×64 | 40×40 |

Ergebnis: **Keine Abweichung.** Der schwebende Button ist auf der Buchungsseite exakt gleich groß wie auf der Hauptseite (Padding 12px, SVG 24px/40px). Kein CSS-Fix nötig; `frontend.scss` bleibt unverändert. Die Beobachtung „größer" war vermutlich ein gecachter/veralteter Stand. Keine Änderung an `frontend.scss`/`frontend.min.css`.
