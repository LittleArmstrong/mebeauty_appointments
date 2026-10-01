# 04: Footer + WhatsApp-Button

**What to build:** Unter dem Buchungs-Wizard erscheint der Website-Footer (vier Spalten: Marke + Social-Icons, Behandlungsliste mit Website-Links, Kontaktdaten, Telefonsprechzeiten; untere Zeile mit Impressum/Datenschutz) und ein schwebender WhatsApp-Button unten rechts.

**Blocked by:** 01: Theme-Fundament + Assets

**Status:** done

- [ ] Footer mit Social-Icons (Instagram, TikTok, WhatsApp), Behandlungsliste (8 Links auf Website-Anker), Kontaktdaten (Telefon, E-Mail, Adresse) und Telefonsprechzeiten sichtbar.
- [ ] Impressum/Datenschutz-Links aus den bestehenden Einstellungen, mit den Website-Seiten als Fallback.
- [ ] Schwebender WhatsApp-Button (wa.me-Link) unten rechts sichtbar.
- [ ] Responsives Raster: 1 Spalte (Mobil) → 2 (md) → 4 (lg).
- [ ] Smoke-Check: Behandlungs-Links, Kontaktdaten, Rechtslinks und WhatsApp-Link im gerenderten HTML vorhanden.

## Comments

Implemented on integration branch `agent/mebeauty-reskin-integration`. Commits: `9e1ca14c` (Footer+WhatsApp), `11e699e5` (Smoke-Check verschärft), `6967b545` (Review-Fixes: Legal-Mapping korrigiert auf EA-Semantik imprint_url=Impressum / legal_notice_url=Datenschutz).

Follow-ups: Footer-Typografie 1:1 zur Website (20px/28px, h2 line-height 40px, p-Margins 0) — `8215d998`; Abstand Wizard↔Footer über Nutzerwahl (`my-5`) — `d2805e89`.
