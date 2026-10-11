# 01: Formatter-Strategie festlegen und für Fork-eigene Dateien durchsetzen

**What to build:** Die Entwicklungskonventionen legen fest: Prettier ist verbindlich für Dateien, die der Fork neu angelegt hat; Upstream-Dateien werden nie als Ganzes formatiert. Der Format-Check findet die Fork-eigenen Dateien selbst über Git und läuft in der CI. Alle Fork-eigenen Dateien sind konform.

**Blocked by:** None (can start immediately)

**Status:** ready-for-agent

- [ ] Regel in den Entwicklungskonventionen dokumentiert, mit kurzer Begründung (Upstream selbst nicht Prettier-konform, Sync-Konflikte)
- [ ] Das Format-Skript prüft automatisch nur Dateien, die seit dem Upstream-Basis-Commit neu sind (innerhalb der bisherigen Pfadmuster), ohne gepflegte Liste
- [ ] Neuer CI-Schritt für den Format-Check
- [ ] Die 8 nicht konformen Fork-eigenen Dateien sind formatiert (konsolidierte Migration, Site-Navbar, MeBeauty-Theme-SCSS, vier Unit-Tests, E-Mail-Smoke-Hilfsskript)
- [ ] Gegenprobe: Eine falsch formatierte neue Datei lässt den Check fehlschlagen, eine Upstream-Datei wird nicht geprüft
- [ ] `composer test`, `php -l`, Layout-Smoke-Test grün
