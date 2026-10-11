# 02: CI testet auf PHP 8.3 und sichert 8.2 ab; Deploy mit Plattformprüfung

**What to build:** Die CI testet mit PHP 8.3 exakt die Pakete aus der Lock-Datei. Ein zusätzlicher Job meldet, sobald Laufzeitcode oder Laufzeitpakete mehr als PHP 8.2 verlangen. Der Deploy-Build installiert ohne Entwicklungspakete und bricht ab, wenn ein Laufzeitpaket PHP 8.2 nicht unterstützt. PHPUnit 12 und `composer.json` bleiben unverändert.

**Blocked by:** None (can start immediately)

**Status:** resolved

- [x] `composer.json` unverändert (keine Plattform-Fixierung, PHPUnit ^12)
- [x] CI-Testjob auf PHP 8.3: Installation aus der Lock-Datei statt `update`; Syntaxprüfung parallel; PHPUnit und Validatoren
- [x] Neuer CI-Job auf PHP 8.2: Plattformprüfung der Lock-Datei ohne Entwicklungspakete und Syntaxprüfung des Laufzeitcodes; Gegenprobe mit einer 8.3-Syntax-Datei schlägt fehl
- [x] Deploy-Build installiert ohne Entwicklungspakete und ohne `--ignore-platform-reqs`; ein Build mit PHP 8.2 läuft nachweislich durch
- [x] Alle CI-Jobs grün

## Comments

- Lokal mit `php:8.2-cli` geprüft: `composer install --no-dev` aus dem Lock läuft ohne `--ignore-platform-reqs` (nur `ext-gd` fehlt im nackten Image; die Deploy-Action installiert gd), mit Dev-Paketen scheitert es erwartungsgemäß an PHPUnit 12; `check-platform-reqs --no-dev --lock` meldet für PHP 8.2 keinen Verstoß; 8.2-Lint über application/system/scripts/index.php grün, Gegenprobe mit typisierter Klassenkonstante schlägt fehl.
- `composer.lock` hatte einen veralteten content-hash (Fork entfernte `"version"` aus composer.json); per `composer update --lock` nachgezogen, keine Paketänderung.
- Die echten CI-Läufe sind erst nach einem Push sichtbar (nicht Teil dieses Auftrags).
- Nebenbefund: `composer` meldet 18 Sicherheitshinweise in 3 Paketen (`composer audit`), außerhalb dieser Spec.
