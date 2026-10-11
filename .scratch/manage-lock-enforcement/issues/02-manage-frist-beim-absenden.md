# 02: Manage-Frist beim Absenden einer Umbuchung prüfen

**What to build:** Bei einer gültigen Umbuchung lädt der Server den ursprünglichen Termin und prüft die Umbuchungs-/Stornierungsfrist gegen dessen gespeicherten Beginn in der Zeitzone des Dienstleisters. Ist die Frist abgelaufen, wird die Umbuchung mit derselben Sperrmeldung abgelehnt, die beim Öffnen des Manage-Links erscheint. Der neue Zeitpunkt unterliegt weiterhin der Buchungsfrist.

**Blocked by:** 01 (Termin-ID nur mit gültigem Manage-Hash akzeptieren)

**Status:** ready-for-agent

- [ ] Umbuchung eines Termins mit abgelaufener Manage-Frist → abgelehnt mit Sperrmeldung inkl. Grenzwert, nichts gespeichert
- [ ] Umbuchung innerhalb der Frist → erfolgreich
- [ ] Maßgeblich ist der gespeicherte Beginn, nicht der Wert aus der Anfrage
- [ ] Die Stornierung bleibt unverändert
- [ ] Das Smoke-Skript aus 01 ist um die Fälle „abgelaufen“ und „innerhalb der Frist“ erweitert und läuft grün
- [ ] PHPUnit, `php -l` und Layout-Smoke-Test grün
