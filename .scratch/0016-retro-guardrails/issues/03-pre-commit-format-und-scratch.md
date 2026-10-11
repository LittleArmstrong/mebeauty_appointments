# 03: Pre-commit prüft Format und Scratch-Index

**What to build:** Der Pre-commit-Hook lehnt einen Commit ab, wenn eine gestagte Fork-Datei nicht Prettier-konform ist oder bei gestagten `.scratch/`-Dateien der Index-Check scheitert. Fehlt das Werkzeug, warnt er und überspringt die Prüfung.

**Blocked by:** None (can start immediately)

**Status:** resolved

- [x] `scripts/check-format.sh --staged` prüft nur Fork-Dateien, die gestagt sind (ACM); ohne Option unverändertes Verhalten
- [x] Hook ruft `check-format.sh --staged` auf, wenn Fork-Dateien gestagt sind; überspringt mit Warnung ohne `npx`
- [x] Hook ruft `php scripts/scratch-index.php check` im Container auf, wenn `.scratch/`-Dateien gestagt sind; überspringt mit Warnung ohne laufenden Container
- [x] Probe: Commit mit falsch formatierter gestagter Fork-Datei abgelehnt, ungestagte falsch formatierte Datei blockiert nicht, veralteter INDEX abgelehnt
- [x] `docs/agents/validation-and-completion.md` beschreibt die neuen Prüfungen

## Comments

- `check-format.sh --staged`: Schnittmenge aus Fork-Dateien und `git diff --cached --diff-filter=ACM`; ohne Option unverändert (`npm run check:format` grün).
- Hook direkt aufgerufen (kein Commit erzeugt): (A) gestagte, falsch formatierte `tests/Unit/ViewEscapingValidatorTest.php` → Exit 1 mit Prettier-Warnung; (B) dieselbe Datei nur ungestagt, gestagt eine saubere Fork-Datei → Exit 0; (C) Ticket-Status geändert ohne `scratch:index` → „INDEX.md ist veraltet“, Exit 1. Probeänderungen zurückgesetzt.
- `php_container_running` prüft jetzt `--status running` statt nur auf erfolgreichen Aufruf von `docker compose ps -q`, damit der Skip-Pfad bei gestopptem Container greift.
