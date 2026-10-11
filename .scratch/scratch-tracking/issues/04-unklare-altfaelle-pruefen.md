# 04: Unklare Altfälle prüfen und Status setzen

**What to build:** Der Status von `0005-mebeauty-dynamic-branding` und `0002-phpunit-cli-bootstrap` entspricht dem tatsächlichen Stand im Code. Jedes Ticket ist entweder `resolved` mit Verweis auf den umsetzenden Commit oder bleibt mit einer kurzen Begründung offen. Der INDEX ist aktualisiert.

**Blocked by:** 03 (Bestand umbenennen, Kopfzeilen nachtragen, INDEX erzeugen)

**Status:** ready-for-agent

- [ ] 0005: alle 7 Tickets einzeln gegen Code und Commits (u. a. „settings-driven branding …“) geprüft; Ergebnis je Ticket unter `## Comments` mit Commit-Verweis
- [ ] 0002: `composer test` ausgeführt; tritt der beschriebene Routing-Fehler nicht mehr auf, ist der behebende Commit ermittelt und das Ticket `resolved`, sonst bleibt es offen mit aktueller Fehlerausgabe
- [ ] Spec-Status von 0002 und 0005 passend gesetzt (resolved nur ohne offene Tickets)
- [ ] INDEX neu erzeugt, Prüfbefehl grün
