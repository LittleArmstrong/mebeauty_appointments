# 03: Einstellung „Sprachauswahl anzeigen“

**What to build:** In den Buchungseinstellungen lässt sich der Sprachumschalter des Booking-Flows aus- und einschalten. Standard ist „anzeigen“, wie bei Upstream. Ist er aus, enthält der Booking-Flow keinen Sprachumschalter. Für MeBeauty ist das Ausschalten als Deployment-Schritt dokumentiert.

**Blocked by:** 0010/01 (zentrale Validierung der Einstellungen)

**Status:** resolved

- [x] Neue Migration mit der nächsten freien Nummer nach dem bestehenden Muster für Einstellungen (Eintrag mit Default 1; beim Zurückrollen wieder löschen); `check:migrations` grün
- [x] Die Regel {0, 1} ist im zentralen Regel-Mechanismus ergänzt und per Unit-Test geprüft
- [x] Die Einstellung ist in den Buchungseinstellungen pflegbar
- [x] Bei 0 ist kein Sprachumschalter im HTML, bei 1 ist er vorhanden; der Smoke-Test prüft beides oder dokumentiert, wie die Einstellung für den Test gesetzt wird
- [x] Die Deployment-Doku nennt das Setzen auf 0 für MeBeauty
- [x] `composer test`, `php -l`, `node --check`, Layout-Smoke-Test grün

## Comments

- Migration `071_add_display_language_selector_setting` (Default 1; `check:migrations` grün; down/up lokal geprüft – beim allerersten Lauf war die Zeile nach `migrate` kurz nicht sichtbar, in drei Wiederholungen danach immer korrekt, Ursache nicht reproduzierbar). Regel {0,1} in der zentralen Validierung (+ Unit-Test), Schalter in den Buchungseinstellungen, Booking-Footer blendet den Umschalter bei 0 aus.
- Geprüft: 0 → kein `#select-language`, 1 → vorhanden; API lehnt `2` ab. Der Layout-Smoke prüft jetzt den Footer statt des Umschalters, weil dieser von der Einstellung abhängt (MeBeauty: 0); das Setzen auf 0 steht in docs/deployment.md („Einstellungen pro Kunde nach Migrationen“).
- Speichern über die Backend-Seite nicht live geprüft (kein Login verfügbar); generische Checkbox-Serialisierung wie bei den übrigen Schaltern.
