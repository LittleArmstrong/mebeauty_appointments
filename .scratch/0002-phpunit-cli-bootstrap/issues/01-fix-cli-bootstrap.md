# 01: PHPUnit-CLI-Bootstrap fixen

**What to build:** Die PHPUnit-Suite läuft wieder lokal (und in der CI): Der Bootstrap darf `index.php` nicht über die CLI-`argv`-Routung führen. Aktuell interpretiert der CI3-Router die PHPUnit-Argumente (`--configuration`, `phpunit.xml`) als Controller/Methode und antwortet mit einem 404, wodurch der Prozess vor dem ersten Test beendet wird.

**Blocked by:** None (can start immediately).

**Status:** resolved

- [x] `vendor/bin/phpunit --configuration phpunit.xml` läuft durch und führt die Tests aus (kein „controller/method pair was not found").
- [x] Der Fix wirkt nur unter PHPUnit/CLI und ändert nichts am normalen Web-Routing.
- [x] Verifiziert gegen `main` und den Arbeitsbranch; CI-Workflow bleibt unverändert grün.

## Comments

- Befund (2026-09-30): `phpunit.xml` bootstrappt `index.php`; `system/core/URI.php:319` (`_parse_argv`) liest `array_slice($_SERVER['argv'], 1)` und der Router 404t darauf. Auf `main` (ohne MeBeauty-Layout-Änderungen) reproduziert — vorab bestehend.
- Mögliche Fix-Richtung: CLI-Guard im Bootstrap (z. B. Ausstieg nach Framework-Load unter `APP_ENV=testing` ohne Router-Durchlauf) oder dedizierte Test-Bootstrap-Datei, die CI ohne Routing lädt.
- Bis dahin dient `tests/smoke/mebeauty-layout-smoke.sh` als funktionierender Verifikations-Seam.

## Comments

- 2026-10-11 (0015/04): Fehler bestand weiter für `vendor/bin/phpunit --configuration phpunit.xml` ohne `APP_ENV` (Exit 4, „controller/method pair was not found“; so ruft auch die CI auf). `composer test` lief nur grün, weil es `APP_ENV=testing` setzt. Ursache: `phpunit.xml` setzte `APP_ENV` nur per `<server>`, `index.php` liest `getenv()`; ohne `testing` greifen die Testrouten (`config/testing/routes.php`) nicht. Fix: zusätzlich `<env name="APP_ENV" value="testing" force="true"/>`. Beide Aufrufe laufen jetzt grün (41 Tests).
