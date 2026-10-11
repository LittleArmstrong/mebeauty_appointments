# 02: CI testet auf PHP 8.3 und sichert 8.2 ab; Deploy mit Plattformprüfung

**What to build:** Die CI testet mit PHP 8.3 exakt die Pakete aus der Lock-Datei. Ein zusätzlicher Job meldet, sobald Laufzeitcode oder Laufzeitpakete mehr als PHP 8.2 verlangen. Der Deploy-Build installiert ohne Entwicklungspakete und bricht ab, wenn ein Laufzeitpaket PHP 8.2 nicht unterstützt. PHPUnit 12 und `composer.json` bleiben unverändert.

**Blocked by:** None (can start immediately)

**Status:** ready-for-agent

- [ ] `composer.json` unverändert (keine Plattform-Fixierung, PHPUnit ^12)
- [ ] CI-Testjob auf PHP 8.3: Installation aus der Lock-Datei statt `update`; Syntaxprüfung parallel; PHPUnit und Validatoren
- [ ] Neuer CI-Job auf PHP 8.2: Plattformprüfung der Lock-Datei ohne Entwicklungspakete und Syntaxprüfung des Laufzeitcodes; Gegenprobe mit einer 8.3-Syntax-Datei schlägt fehl
- [ ] Deploy-Build installiert ohne Entwicklungspakete und ohne `--ignore-platform-reqs`; ein Build mit PHP 8.2 läuft nachweislich durch
- [ ] Alle CI-Jobs grün
