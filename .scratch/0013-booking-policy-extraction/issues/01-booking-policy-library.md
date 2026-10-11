# 01: Booking-Policy als einzige Implementierung von Fristen und Sperren

**What to build:** Eine eigene Library Booking-Policy berechnet Buchungsfrist, Manage-Frist, Terminsperren und den frühesten buchbaren Zeitpunkt eines Tages. Uhr und Einstellungsquelle sind injizierbar. Verfügbarkeit, Buchungsseite, Umbuchen (Absenden) und Stornierung nutzen nur noch die Policy. Die Grenze im Minuten-Modus ist vereinheitlicht: Ein Termin genau zur Mindestvorlaufzeit ist buchbar und verwaltbar. Sonst ändert sich kein Verhalten.

**Blocked by:** 0009/02, 0012/02

**Status:** resolved

- [x] Öffentliche Schnittstelle: Sperre eines Termins (Beginn, Zeitzone, Art), Frist eines Tages, frühester buchbarer Zeitpunkt eines Tages
- [x] Uhr (Default echte Zeit) und Einstellungsquelle (Default Settings-Helper) injizierbar; Einstellungen einmal je Instanz gelesen
- [x] Die Verfügbarkeits-Library enthält keine Frist- oder Sperrberechnung mehr (Buchungs-/Manage-Frist, Buchungs-/Manage-Sperre, Fristprüfung, Vorlauf-Normalisierung entfernt bzw. an die Policy delegiert)
- [x] Je ein gemeinsamer Baustein für Festzeit-Frist, Minuten-Grenze und ganzzahlige Einstellung ≥ 0
- [x] Grenzsemantik: Minuten-Modus zulässig bei `Beginn ≥ jetzt + Vorlaufzeit` (Slot-Filter und Sperre gleich); Festzeit offen bei `jetzt ≤ Frist`
- [x] Unit-Tests mit fester Uhr: Festzeit 18:00:00 offen / 18:00:01 gesperrt; Tagesabstand 0; Minuten-Grenze 11:00 zulässig / 10:59 nicht; Manage inherit/minutes/fixed_time; abweichende Zeitzone des Dienstleisters; Sommerzeitwechsel; Festzeit nach Ablauf → Tag gesperrt
- [x] Smoke-Skript aus 0009, Layout-Smoke-Test, `composer test`, `php -l` grün

## Comments

- `application/libraries/Booking_policy.php` mit `get_lock()`, `get_booking_deadline()`, `get_earliest_booking_start()`; Uhr, Einstellungen und Datumsformatierer injizierbar (der Formatierer, weil `format_date_time()` das Datumsformat aus der Datenbank liest). Aus `Availability` entfernt: Buchungs-/Manage-Frist, Buchungs-/Manage-Sperre, Fristprüfung, Vorlauf-Normalisierung; Slot-Filter fragt `get_earliest_booking_start()` (Gleichheit erlaubt, vereinheitlicht).
- 9 Unit-Tests mit fester Uhr (inkl. Berlin/UTC und Sommerzeitwechsel). Smoke (Absenden, Fristen) grün; Sperre beim Öffnen des Manage-Links und bei der Stornierung live geprüft.
