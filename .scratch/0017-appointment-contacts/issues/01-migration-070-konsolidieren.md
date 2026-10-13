# 01: Migration 070 konsolidieren

**What to build:** Vorab-Refactoring nach ADR 0005 („bis zum ersten Prod-Release fließt alles in 070“). Die Einstellung „Sprachauswahl anzeigen“ aus Migration 071 geht in Migration 070 auf, die Datei 071 entfällt. Migration 070 wird idempotent und legt nur an, was fehlt. Eine frische Installation und eine Datenbank, die von Version 69 aus erneut migriert wird, haben danach dasselbe Schema und dieselben Einstellungen. Das Verhalten der App ändert sich nicht.

**Blocked by:** None (can start immediately)

**Status:** resolved

- [x] 071 ist entfernt, 070 enthält die Einstellung `display_language_selector` (Default 1); die Nummernfolge bleibt lückenlos bis 070
- [x] 070 ist idempotent: ein zweiter Lauf auf einer bereits migrierten Datenbank (Version auf 69 zurückgesetzt) bricht nicht ab und ändert vorhandene Werte nicht
- [x] `down()` entfernt weiterhin alles, was 070 anlegt
- [x] `check:migrations` grün; frische Installation und „Version 69 + migrate“ lokal geprüft, Beleg im Ticket
- [x] Verweise auf Migration 071 in Doku und Specs (z. B. Deployment-Doku, Kommentar in 070) sind angepasst
- [x] `bash scripts/test.sh` grün

## Comments

- `071_add_display_language_selector_setting.php` entfernt; `display_language_selector` (Default 1) steht in 070 (Insert nur, wenn die Zeile fehlt; in `down()` mitgelöscht). Dateiname von 070 bleibt (ADR 0005 und `SettingValidationTest` verweisen darauf), der Docblock beschreibt 070 jetzt als Sammelmigration bis zum ersten Prod-Release.
- Beleg (`scripts/migration-test-db.sh`): `fresh` → Version 70; `copy easyappointments --set-version 69` → Version 70, Tabelle + Einstellungen vorhanden; zweiter Lauf ab 69 nach `display_language_selector = 0` → Wert bleibt 0 (idempotent); `migrate down` → Version 69, Fork-Einstellungen 0, `appointment_contacts` und `slot_capacity` weg; `migrate up` → wieder 70.
- Lokale DB: `UPDATE ea_migrations SET version = 69` + `console migrate` → 70. Doku (ADR 0005, `docs/deployment.md`) war bereits angepasst; Hinweis in Spec 0019 aktualisiert. Historische Erwähnungen in 0006/0007/0014 und `docs/superpowers/` bleiben als Geschichte stehen.
- `bash scripts/test.sh` grün.
