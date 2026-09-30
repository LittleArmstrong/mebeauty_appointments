# 05: Feinschliff + Smoke-Test + Doku

**What to build:** Der Buchungs-Kopfbereich (Firmenlogo/-name) entfällt, der 4-Schritte-Indikator bleibt als eigene Komponente erhalten; Browser-Titel, OG-Meta und Theme-Farbe werden auf MeBeauty angepasst. Der HTTP-Smoke-Test läuft grün, GLOSSARY.md und die ADR zum SCSS-Nachbau sind angelegt, und der Session-Report liegt vor.

**Blocked by:** 02: Website-Navbar, 03: Intro-Hero „Buchung", 04: Footer + WhatsApp-Button

**Status:** ready-for-agent

- [ ] Kein Firmenlogo/-name mehr im Wizard (Branding übernimmt die Navbar); Schritt-Indikator (Step 1–4) weiterhin sichtbar.
- [ ] Sprachumschalter und Login-/Backend-Link im Card-Footer weiterhin vorhanden.
- [ ] Browser-Titel „Buchung | MeBeauty – Termin online buchen", OG-Meta und Theme-Farbe Gold aktiv; kein „Easy!Appointments"-Branding auf der Buchungsseite.
- [ ] Smoke-Test läuft grün (alle Checks inkl. Abwesenheits-Checks).
- [ ] Gulp-Build und `php -l` auf allen geänderten Views fehlerfrei.
- [ ] GLOSSARY.md (Booking-Flow, Wizard-Card, mebeauty-Site) und ADR „MeBeauty-Design als SCSS/Bootstrap-Theme" angelegt.
- [ ] Session-Report unter docs/agent-sessions angelegt.
