# 01: Smoke-Fixture allgemein benennen

**What to build:** Die Smoke-Fixture heißt `tests/smoke/fixtures.php`. Alle Aufrufe, der Kopfkommentar und der interne Skriptname folgen dem neuen Namen.

**Blocked by:** None (can start immediately)

**Status:** ready-for-agent

- [ ] `tests/smoke/manage-link-fixtures.php` ist per `git mv` nach `tests/smoke/fixtures.php` umbenannt
- [ ] `manage-link-smoke.sh` und `language-selector-smoke.sh` rufen die neue Datei auf
- [ ] Außerhalb abgeschlossener Specs findet `grep -r manage-link-fixtures` nichts mehr
- [ ] Smoke `manage-link` und `language-selector` sind grün
