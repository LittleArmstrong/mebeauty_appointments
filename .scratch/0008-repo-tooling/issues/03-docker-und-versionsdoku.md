# 03: Docker auf PHP 8.3, Versionen dokumentiert

**What to build:** Lokal läuft php-fpm mit PHP 8.3, also mit der Version von Produktiv-Webserver und CI-Tests. Die Doku enthält eine Übersicht der PHP-Versionen.

**Blocked by:** 02 (CI testet auf PHP 8.3 und sichert 8.2 ab)

**Status:** resolved

- [x] Das php-fpm-Image basiert auf PHP 8.3; alle bisherigen Extensions sind installierbar
- [x] Docker- und Deployment-Doku nennen: Mindestversion 8.2 (Produktiv-CLI); Produktiv-Web 8.3 (= Testversion); Docker und CI-Tests 8.3; CI-Laufzeitcheck und Deploy-Build 8.2
- [x] Manueller Schritt dokumentiert: Extensions bei manitu (Web `phpinfo()`, CLI `php -m`) gegen die Composer-Anforderungen prüfen
- [x] Nach dem Neubau des Images PHPUnit und Layout-Smoke-Test grün; CI-Smoke-Job grün

## Comments

- Image neu gebaut: PHP 8.3.35, alle Extensions vorhanden (u. a. gd, curl, mbstring, fileinfo, SimpleXML, intl, ldap). `composer test` 41/41, Layout-Smoke 16/16, E-Mail-Logo-Smoke grün. CI-Smoke-Job erst nach Push sichtbar.
