# 03: Docker auf PHP 8.3, Versionsmatrix dokumentiert

**What to build:** Lokal läuft php-fpm mit PHP 8.3 wie der Produktiv-Webserver. Die Doku nennt die Versionsmatrix: Prod Web 8.3, Prod CLI 8.2, Untergrenze 8.2, CI 8.2 + 8.3.

**Blocked by:** 02 (PHP-Untergrenze 8.2, CI-Matrix 8.2/8.3, Deploy prüft Plattform)

**Status:** ready-for-agent

- [ ] Das php-fpm-Image basiert auf PHP 8.3
- [ ] Die Docker- und Deployment-Doku enthält die Versionsmatrix
- [ ] Nach dem Neubau des Images sind PHPUnit und Layout-Smoke-Test grün
- [ ] Der CI-Smoke-Job ist grün
