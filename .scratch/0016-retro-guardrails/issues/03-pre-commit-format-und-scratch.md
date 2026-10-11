# 03: Pre-commit prüft Format und Scratch-Index

**What to build:** Der Pre-commit-Hook lehnt einen Commit ab, wenn eine gestagte Fork-Datei nicht Prettier-konform ist oder bei gestagten `.scratch/`-Dateien der Index-Check scheitert. Fehlt das Werkzeug, warnt er und überspringt die Prüfung.

**Blocked by:** None (can start immediately)

**Status:** ready-for-agent

- [ ] `scripts/check-format.sh --staged` prüft nur Fork-Dateien, die gestagt sind (ACM); ohne Option unverändertes Verhalten
- [ ] Hook ruft `check-format.sh --staged` auf, wenn Fork-Dateien gestagt sind; überspringt mit Warnung ohne `npx`
- [ ] Hook ruft `php scripts/scratch-index.php check` im Container auf, wenn `.scratch/`-Dateien gestagt sind; überspringt mit Warnung ohne laufenden Container
- [ ] Probe: Commit mit falsch formatierter gestagter Fork-Datei abgelehnt, ungestagte falsch formatierte Datei blockiert nicht, veralteter INDEX abgelehnt
- [ ] `docs/agents/validation-and-completion.md` beschreibt die neuen Prüfungen
