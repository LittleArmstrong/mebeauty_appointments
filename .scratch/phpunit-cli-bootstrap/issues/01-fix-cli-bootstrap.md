# 01: PHPUnit-CLI-Bootstrap fixen

**What to build:** Die PHPUnit-Suite läuft wieder lokal (und in der CI): Der Bootstrap darf `index.php` nicht über die CLI-`argv`-Routung führen. Aktuell interpretiert der CI3-Router die PHPUnit-Argumente (`--configuration`, `phpunit.xml`) als Controller/Methode und antwortet mit einem 404, wodurch der Prozess vor dem ersten Test beendet wird.

**Blocked by:** None (can start immediately).

**Status:** ready-for-agent

- [ ] `vendor/bin/phpunit --configuration phpunit.xml` läuft durch und führt die Tests aus (kein „controller/method pair was not found").
- [ ] Der Fix wirkt nur unter PHPUnit/CLI und ändert nichts am normalen Web-Routing.
- [ ] Verifiziert gegen `main` und den Arbeitsbranch; CI-Workflow bleibt unverändert grün.

## Comments

- Befund (2026-09-30): `phpunit.xml` bootstrappt `index.php`; `system/core/URI.php:319` (`_parse_argv`) liest `array_slice($_SERVER['argv'], 1)` und der Router 404t darauf. Auf `main` (ohne MeBeauty-Layout-Änderungen) reproduziert — vorab bestehend.
- Mögliche Fix-Richtung: CLI-Guard im Bootstrap (z. B. Ausstieg nach Framework-Load unter `APP_ENV=testing` ohne Router-Durchlauf) oder dedizierte Test-Bootstrap-Datei, die CI ohne Routing lädt.
- Bis dahin dient `tests/smoke/mebeauty-layout-smoke.sh` als funktionierender Verifikations-Seam.
