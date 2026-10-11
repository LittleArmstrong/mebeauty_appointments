# 02: INDEX-Generator und Prüfbefehl

**What to build:** Ein PHP-Skript erzeugt `.scratch/INDEX.md` aus den Spec-Kopfzeilen und dem Ticket-Status (offen/abgeschlossen, Priorität, Daten, Abhängigkeiten, Tickets offen/gesamt) und prüft die Konsistenzregeln der Spec. Je ein Composer-Befehl erzeugt und prüft den INDEX, und die CI führt die Prüfung aus.

**Blocked by:** 01 (Tracking-Schema in der Agenten-Doku festlegen)

**Status:** resolved

- [x] Skript unter `scripts/` im Stil der vorhandenen Validatoren, mit Modus „erzeugen“ und Modus „prüfen“ (Exit-Code ≠ 0 bei Verstoß, verständliche Meldung je Verstoß)
- [x] Parse- und Prüflogik als reine, ohne Dateisystem-Seiteneffekte testbare Einheit
- [x] Prüfregeln: Pflichtfelder, gültige Werte und Datumsformat, eindeutige und lückenlose Nummern, Depends-on nur auf existierende Specs, resolved ohne offene Tickets, INDEX entspricht der generierten Fassung
- [x] INDEX-Format laut Spec (zwei Abschnitte, Sortierung, Hinweis „generiert“)
- [x] Composer-Befehle nach dem Muster der vorhandenen `check:*`-Befehle; CI-Schritt im Testjob, zunächst nur, wenn der Bestand bereits migriert ist (sonst in Ticket 03 aktivieren)
- [x] Unit-Tests mit Fixtures: gültig, fehlender Status, unbekannter Status, ungültiges Datum, unbekannte Abhängigkeit, resolved mit offenem Ticket, doppelte Nummer, veralteter INDEX
- [x] `composer test` und `php -l` grün

## Comments

- CI-Schritt wird erst in 03 aktiviert, weil der Bestand vorher nicht dem Schema entspricht.
