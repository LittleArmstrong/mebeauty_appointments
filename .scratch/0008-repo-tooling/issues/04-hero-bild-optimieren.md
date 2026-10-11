# 04: Hero-Bild komprimieren und Layout-Sprung vermeiden

**What to build:** Das Fallback-JPG des Hero-Bilds im Booking-Flow ist höchstens 300 KB groß, sieht aber weiterhin hochwertig aus. Das Bild hat Breite, Höhe und hohe Lade-Priorität, damit die Seite beim Laden nicht springt.

**Blocked by:** None (can start immediately)

**Status:** resolved

- [x] Das JPG ist ≤ 300 KB bei gleichen Abmessungen
- [x] Das Bild-Element hat die echten Pixelmaße als Breite/Höhe und hohe Lade-Priorität
- [x] Die WebP-Quelle bleibt unverändert
- [x] Sichtprüfung der Qualität; Layout-Smoke-Test grün

## Comments

- Mit PHP-GD neu komprimiert (Qualität 70, progressiv): 1,8 MB → 290 KB bei 3400×2240. Sichtvergleich eines 100-%-Ausschnitts ohne erkennbaren Unterschied. `width`/`height`/`fetchpriority` gesetzt; das CSS (`width/height: 100%`, `object-fit: cover`) bestimmt weiter die Darstellung.
