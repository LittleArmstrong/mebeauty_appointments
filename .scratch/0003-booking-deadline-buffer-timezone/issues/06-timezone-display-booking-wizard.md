# 06: Timezone-Anzeige im Buchungs-Wizard

**What to build:** `booking_timezone_display` steuert, wo die Zeitzone im Buchungs-Wizard erscheint: `dropdown` (wie bisher), `confirm_only` (Dropdown versteckt, Zeitzonen-Zeile im Bestätigungs-Frame bleibt sichtbar) oder `hidden` (beides versteckt). Ist das Dropdown ausgeblendet, legt `booking_timezone_source` fest, welche Zeitzone für Anzeige und Buchung verwendet wird: `company` nutzt die Firmen-Default-Zeitzone (kein Browser-Autodetect), `browser` behält das bisherige Autodetect-Verhalten. Zeiten-Umrechnung und Buchungsablauf funktionieren in allen Kombinationen.

**Blocked by:** 03

**Status:** resolved

- [ ] `hidden` + Quelle `company`: keine Zeitzonen-UI im Wizard; Zeiten in Firmen-Default-Zeitzone
- [ ] `confirm_only`: Dropdown ausgeblendet, Zeitzonen-Zeile im Bestätigungs-Frame sichtbar
- [ ] `dropdown`: Verhalten unverändert (inkl. Browser-Autodetect-Vorbelegung)
- [ ] `hidden` + Quelle `browser`: Zeiten in Browser-Zeitzone (Autodetect bleibt aktiv)
- [ ] Buchung in allen Kombinationen erfolgreich; Zeitzonen-Umrechnung der Slots bleibt korrekt
- [ ] `npm run build` läuft fehlerfrei
