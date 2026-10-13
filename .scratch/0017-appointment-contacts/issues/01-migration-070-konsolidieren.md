# 01: Migration 070 konsolidieren

**What to build:** Vorab-Refactoring nach ADR 0005 („bis zum ersten Prod-Release fließt alles in 070“). Die Einstellung „Sprachauswahl anzeigen“ aus Migration 071 geht in Migration 070 auf, die Datei 071 entfällt. Migration 070 wird idempotent und legt nur an, was fehlt. Eine frische Installation und eine Datenbank, die von Version 69 aus erneut migriert wird, haben danach dasselbe Schema und dieselben Einstellungen. Das Verhalten der App ändert sich nicht.

**Blocked by:** None (can start immediately)

**Status:** ready-for-agent

- [ ] 071 ist entfernt, 070 enthält die Einstellung `display_language_selector` (Default 1); die Nummernfolge bleibt lückenlos bis 070
- [ ] 070 ist idempotent: ein zweiter Lauf auf einer bereits migrierten Datenbank (Version auf 69 zurückgesetzt) bricht nicht ab und ändert vorhandene Werte nicht
- [ ] `down()` entfernt weiterhin alles, was 070 anlegt
- [ ] `check:migrations` grün; frische Installation und „Version 69 + migrate“ lokal geprüft, Beleg im Ticket
- [ ] Verweise auf Migration 071 in Doku und Specs (z. B. Deployment-Doku, Kommentar in 070) sind angepasst
- [ ] `bash scripts/test.sh` grün
