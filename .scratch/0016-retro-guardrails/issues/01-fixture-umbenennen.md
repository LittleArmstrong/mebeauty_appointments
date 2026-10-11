# 01: Smoke-Fixture allgemein benennen

**What to build:** Die Smoke-Fixture heißt `tests/smoke/fixtures.php`. Alle Aufrufe, der Kopfkommentar und der interne Skriptname folgen dem neuen Namen.

**Blocked by:** None (can start immediately)

**Status:** resolved

- [x] `tests/smoke/manage-link-fixtures.php` ist per `git mv` nach `tests/smoke/fixtures.php` umbenannt
- [x] `manage-link-smoke.sh` und `language-selector-smoke.sh` rufen die neue Datei auf
- [x] Außerhalb abgeschlossener Specs findet `grep -r manage-link-fixtures` nichts mehr
- [x] Smoke `manage-link` und `language-selector` sind grün

## Comments

- Umbenannt per `git mv`, Aufrufe in beiden Smoke-Skripten und Kopfkommentar angepasst. Kein Verhaltenswechsel, daher kein Rot-Beleg. Smoke `manage-link` 9/9, `language-selector` 2/2 grün; `php -l` und Prettier sauber.
