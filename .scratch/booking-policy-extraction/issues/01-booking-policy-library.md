# 01: Booking-Policy für Fristen und Sperren mit Unit-Tests

**What to build:** Eine eigene Library Booking-Policy beantwortet alle Fragen zu Buchungsfrist, Manage-Frist und Sperren. Die „jetzt“-Zeit und die Settings-Quelle sind injizierbar, die Settings werden einmal je Instanz gelesen. Verfügbarkeit, Buchungsseite (Öffnen und Absenden einer Umbuchung) und Stornierung nutzen ausschließlich die Policy. Das Verhalten für Kunden bleibt exakt gleich.

**Blocked by:** manage-lock-enforcement/02, buffer-end-of-day/02

**Status:** ready-for-agent

- [ ] Öffentliche Schnittstelle: Sperre eines Termins (Beginn, Zeitzone, Art), Frist eines Tages für Neubuchungen, frühester buchbarer Zeitpunkt eines Tages
- [ ] Je ein gemeinsamer interner Baustein für die Festzeit-Frist, die Minuten-Sperre und ganzzahlige Settings ≥ 0 (keine Duplikate mehr)
- [ ] Die Verfügbarkeits-Library enthält keine Frist- und Sperrberechnung mehr
- [ ] Unit-Tests mit fester „jetzt“-Zeit und vorgegebenen Settings: Festzeit 17:59 offen / 18:00 gesperrt; Tagesabstand 0; Minuten-Modus 119/121; Manage „übernehmen“ = Buchungsfrist; Manage „Minuten“ und „feste Uhrzeit“; andere Zeitzone des Dienstleisters; Sommerzeitwechsel; Festzeit nach Ablauf → ganzer Tag gesperrt
- [ ] Smoke-Skript aus manage-lock-enforcement und Layout-Smoke-Test grün
- [ ] PHPUnit und `php -l` grün
