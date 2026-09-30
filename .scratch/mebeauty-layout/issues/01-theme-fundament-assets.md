# 01: Theme-Fundament + Assets

**What to build:** Die Buchungsseite rendert im dunklen MeBeauty-Look: Das Bootstrap-Theme „mebeauty" (Gold-Akzente, Schwarz als Seitenhintergrund, selbst gehostete Karla-/Dancing-Script-Schriften) kompiliert fehlerfrei im Gulp-Build, die Buchungsseite lädt es fest, und Logo, Hero-Bild und Social-Media-Icons liegen im Repo. Die Wizard-Card bleibt hell.

**Blocked by:** None (can start immediately).

**Status:** ready-for-agent

- [ ] Fonts Karla (400/500/600/700) und Dancing Script (400/600/700) als WOFF2 selbst gehostet (keine Google-Fonts-Requests).
- [ ] Bilder (Logo, Hero, Instagram/TikTok/WhatsApp-Icons) aus dem Website-Projekt übernommen.
- [ ] Theme „mebeauty" (Gold `#d1ae5e`, Schwarz, Karla-Basisschrift) kompiliert im Gulp-Build fehlerfrei.
- [ ] Buchungsseite nutzt das Theme über die Theme-Einstellung („mebeauty" als Option, per Migration als Standard gesetzt, explizite andere Wahlen bleiben erhalten); die Wizard-Card bleibt hell.
- [ ] Smoke-Check: Buchungsseite liefert HTTP 200 mit mebeauty-Theme-CSS und Seiten-Hintergrund-Klasse.
