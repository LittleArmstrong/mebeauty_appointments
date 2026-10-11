# 01: Formatter-Strategie festlegen und für Fork-eigene Dateien durchsetzen

**What to build:** Die Entwicklungskonventionen legen fest: Prettier ist verbindlich für Dateien, die der Fork neu angelegt hat; Upstream-Dateien werden nie als Ganzes formatiert. Der Format-Check findet die Fork-eigenen Dateien selbst über Git und läuft in der CI. Alle Fork-eigenen Dateien sind konform.

**Blocked by:** None (can start immediately)

**Status:** resolved

- [x] Regel in den Entwicklungskonventionen dokumentiert, mit kurzer Begründung (Upstream selbst nicht Prettier-konform, Sync-Konflikte)
- [x] Das Format-Skript prüft automatisch nur Dateien, die seit dem Upstream-Basis-Commit neu sind (innerhalb der bisherigen Pfadmuster), ohne gepflegte Liste
- [x] Neuer CI-Schritt für den Format-Check
- [x] Die 8 nicht konformen Fork-eigenen Dateien sind formatiert (konsolidierte Migration, Site-Navbar, MeBeauty-Theme-SCSS, vier Unit-Tests, E-Mail-Smoke-Hilfsskript)
- [x] Gegenprobe: Eine falsch formatierte neue Datei lässt den Check fehlschlagen, eine Upstream-Datei wird nicht geprüft
- [x] `composer test`, `php -l`, Layout-Smoke-Test grün

## Comments

- `scripts/check-format.sh` (über `npm run check:format` / `npm run format`) ermittelt Fork-eigene Dateien per `git diff --diff-filter=A <UPSTREAM-Basis>` gegen den Arbeitsstand plus neue, nicht versionierte Dateien. CI: `fetch-depth: 0` und neuer Schritt im Testjob. Die 8 Dateien sind formatiert; das kompilierte Theme-CSS ist danach byte-identisch, Tests und Smoke grün. Gegenprobe: falsch formatierte neue Datei → Exit 1; nicht konforme Upstream-Dateien werden nicht geprüft.
