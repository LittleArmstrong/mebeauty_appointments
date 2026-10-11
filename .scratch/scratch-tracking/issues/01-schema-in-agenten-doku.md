# 01: Tracking-Schema in der Agenten-Doku festlegen

**What to build:** Die Agenten-Doku beschreibt verbindlich, wie Specs nummeriert und mit Kopfzeilen versehen werden, welche Status-Werte gelten, wie Specs und Tickets über Specs hinweg referenziert werden und mit welchen Befehlen der INDEX erzeugt und geprüft wird. Agenten und Skills, die die Tracker-Doku lesen, legen neue Specs danach direkt im richtigen Schema an.

**Blocked by:** None (can start immediately)

**Status:** resolved

- [x] Die Tracker-Doku beschreibt: Ordner `NNNN-<slug>` (nächste freie Nummer, nie wiederverwendet), Kopfzeilen Status/Created/Closed/Priority/Depends-on mit erlaubten Werten, Ticket-Verweise `NNNN/NN`, INDEX als generierte Datei, Befehle zum Erzeugen und Prüfen
- [x] Die Triage-Label-Doku nennt die Lebenszyklus-Werte `in-progress` und `resolved` (zusätzlich zu den fünf Triage-Labels); `done` ist nicht mehr zulässig
- [x] AGENTS.md verweist knapp auf `.scratch/INDEX.md` und den Prüfbefehl
- [x] Die Wayfinding-Abschnitte der Tracker-Doku widersprechen dem Schema nicht
