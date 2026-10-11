# 01: Booking-Policy als einzige Implementierung von Fristen und Sperren

**What to build:** Eine eigene Library Booking-Policy berechnet Buchungsfrist, Manage-Frist, Terminsperren und den frühesten buchbaren Zeitpunkt eines Tages. Uhr und Einstellungsquelle sind injizierbar. Verfügbarkeit, Buchungsseite, Umbuchen (Absenden) und Stornierung nutzen nur noch die Policy. Die Grenze im Minuten-Modus ist vereinheitlicht: Ein Termin genau zur Mindestvorlaufzeit ist buchbar und verwaltbar. Sonst ändert sich kein Verhalten.

**Blocked by:** manage-lock-enforcement/02, buffer-end-of-day/02

**Status:** ready-for-agent

- [ ] Öffentliche Schnittstelle: Sperre eines Termins (Beginn, Zeitzone, Art), Frist eines Tages, frühester buchbarer Zeitpunkt eines Tages
- [ ] Uhr (Default echte Zeit) und Einstellungsquelle (Default Settings-Helper) injizierbar; Einstellungen einmal je Instanz gelesen
- [ ] Die Verfügbarkeits-Library enthält keine Frist- oder Sperrberechnung mehr (Buchungs-/Manage-Frist, Buchungs-/Manage-Sperre, Fristprüfung, Vorlauf-Normalisierung entfernt bzw. an die Policy delegiert)
- [ ] Je ein gemeinsamer Baustein für Festzeit-Frist, Minuten-Grenze und ganzzahlige Einstellung ≥ 0
- [ ] Grenzsemantik: Minuten-Modus zulässig bei `Beginn ≥ jetzt + Vorlaufzeit` (Slot-Filter und Sperre gleich); Festzeit offen bei `jetzt ≤ Frist`
- [ ] Unit-Tests mit fester Uhr: Festzeit 18:00:00 offen / 18:00:01 gesperrt; Tagesabstand 0; Minuten-Grenze 11:00 zulässig / 10:59 nicht; Manage inherit/minutes/fixed_time; abweichende Zeitzone des Dienstleisters; Sommerzeitwechsel; Festzeit nach Ablauf → Tag gesperrt
- [ ] Smoke-Skript aus manage-lock-enforcement, Layout-Smoke-Test, `composer test`, `php -l` grün
