# 03: Einstellung „Sprachauswahl anzeigen“

**What to build:** In den Buchungseinstellungen lässt sich der Sprachumschalter des Booking-Flows aus- und einschalten. Standard ist „anzeigen“, wie bei Upstream. Ist er aus, enthält der Booking-Flow keinen Sprachumschalter. Für MeBeauty ist das Ausschalten als Deployment-Schritt dokumentiert.

**Blocked by:** input-hardening/01 (zentrale Validierung der Einstellungen)

**Status:** ready-for-agent

- [ ] Neue Migration mit der nächsten freien Nummer nach dem bestehenden Muster für Einstellungen (Eintrag mit Default 1; beim Zurückrollen wieder löschen); `check:migrations` grün
- [ ] Die Regel {0, 1} ist im zentralen Regel-Mechanismus ergänzt und per Unit-Test geprüft
- [ ] Die Einstellung ist in den Buchungseinstellungen pflegbar
- [ ] Bei 0 ist kein Sprachumschalter im HTML, bei 1 ist er vorhanden; der Smoke-Test prüft beides oder dokumentiert, wie die Einstellung für den Test gesetzt wird
- [ ] Die Deployment-Doku nennt das Setzen auf 0 für MeBeauty
- [ ] `composer test`, `php -l`, `node --check`, Layout-Smoke-Test grün
