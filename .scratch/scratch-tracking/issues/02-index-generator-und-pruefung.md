# 02: INDEX-Generator und Prüfbefehl

**What to build:** Ein PHP-Skript erzeugt `.scratch/INDEX.md` aus den Spec-Kopfzeilen und dem Ticket-Status (offen/abgeschlossen, Priorität, Daten, Abhängigkeiten, Tickets offen/gesamt) und prüft die Konsistenzregeln der Spec. Je ein Composer-Befehl erzeugt und prüft den INDEX, und die CI führt die Prüfung aus.

**Blocked by:** 01 (Tracking-Schema in der Agenten-Doku festlegen)

**Status:** ready-for-agent

- [ ] Skript unter `scripts/` im Stil der vorhandenen Validatoren, mit Modus „erzeugen“ und Modus „prüfen“ (Exit-Code ≠ 0 bei Verstoß, verständliche Meldung je Verstoß)
- [ ] Parse- und Prüflogik als reine, ohne Dateisystem-Seiteneffekte testbare Einheit
- [ ] Prüfregeln: Pflichtfelder, gültige Werte und Datumsformat, eindeutige und lückenlose Nummern, Depends-on nur auf existierende Specs, resolved ohne offene Tickets, INDEX entspricht der generierten Fassung
- [ ] INDEX-Format laut Spec (zwei Abschnitte, Sortierung, Hinweis „generiert“)
- [ ] Composer-Befehle nach dem Muster der vorhandenen `check:*`-Befehle; CI-Schritt im Testjob, zunächst nur, wenn der Bestand bereits migriert ist (sonst in Ticket 03 aktivieren)
- [ ] Unit-Tests mit Fixtures: gültig, fehlender Status, unbekannter Status, ungültiges Datum, unbekannte Abhängigkeit, resolved mit offenem Ticket, doppelte Nummer, veralteter INDEX
- [ ] `composer test` und `php -l` grün
