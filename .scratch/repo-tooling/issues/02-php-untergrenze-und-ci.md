# 02: PHP-Untergrenze 8.2, CI-Matrix 8.2/8.3, Deploy prüft Plattform

**What to build:** Die Abhängigkeiten werden für PHP 8.2 aufgelöst. Die CI testet mit PHP 8.2 und 8.3 exakt die Paketversionen aus der Lock-Datei und prüft die Syntax parallel. Der Deploy-Build bricht ab, wenn ein Paket die PHP-Version nicht unterstützt.

**Blocked by:** None (can start immediately)

**Status:** ready-for-agent

- [ ] Die Composer-Konfiguration fixiert die Plattform-PHP-Version auf 8.2; die Lock-Datei ist neu aufgelöst, Versionsänderungen sind im Commit dokumentiert
- [ ] CI-Testjob als Matrix 8.2 + 8.3 mit Installation aus der Lock-Datei
- [ ] Die Syntaxprüfung läuft parallel
- [ ] Der Deploy-Build installiert ohne Ignorieren der Plattform-Anforderungen
- [ ] Beide Matrix-Zweige und der Smoke-Job sind in der CI grün; ein lokaler Probe-Build des Deploy-Tasks läuft durch
