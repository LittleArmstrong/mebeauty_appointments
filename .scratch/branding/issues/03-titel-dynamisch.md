# 03: Titel dynamisch

**What to build:** Alle Seiten- und E-Mail-Titel enden auf „| MeBeauty“ aus dem Firmennamen-Setting; Installation und Fehlerseiten zeigen statisch „MeBeauty“; der Buchungsseiten-Titel ist auf „Buchung | <Firmenname>“ vereinfacht.

**Blocked by:** 01 Firmenname korrigieren

**Status:** ready-for-agent

- [ ] Backend-, Account-, Message- und Booking-Layout-Titel sowie `og:title` verwenden den Firmennamen aus `company_name` (Fallback „MeBeauty“)
- [ ] Die vier E-Mail-Templates verwenden denselben Firmennamen
- [ ] Installation und die drei Fehlerseiten zeigen „MeBeauty“ (statisch, da dort keine Datenbank garantiert ist)
- [ ] Buchungsseiten-Titel ohne die Tagline „– Termin online buchen“
- [ ] Bereits vorhandene unkommittete Text-/Layout-Änderungen bleiben erhalten
