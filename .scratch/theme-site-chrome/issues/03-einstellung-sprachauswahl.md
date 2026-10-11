# 03: Einstellung „Sprachauswahl anzeigen“

**What to build:** In den Buchungseinstellungen lässt sich der Sprachumschalter des Booking-Flows aus- und einschalten. Standard ist „anzeigen“ (Upstream-Verhalten). Ist er aus, enthält der Booking-Flow keinen Sprachumschalter. Für MeBeauty ist das Ausschalten als Deployment-Schritt dokumentiert.

**Blocked by:** input-hardening/01 (Validierung der Fork-Settings)

**Status:** ready-for-agent

- [ ] Neue Migration mit der nächsten freien Nummer legt die Einstellung mit Default 1 an; der Migrations-Validator ist grün
- [ ] Die Einstellung ist in den Buchungseinstellungen pflegbar und wird als 0/1 validiert
- [ ] Bei 0 ist kein Sprachumschalter im HTML, bei 1 ist er vorhanden; der Smoke-Test prüft beides oder dokumentiert, wie die Einstellung für den Test gesetzt wird
- [ ] Die Deployment-Doku nennt das Setzen auf 0 für MeBeauty
- [ ] PHPUnit, `php -l`, `node --check` und Layout-Smoke-Test grün
