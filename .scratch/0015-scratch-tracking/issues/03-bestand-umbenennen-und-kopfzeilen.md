# 03: Bestand umbenennen, Kopfzeilen nachtragen, INDEX erzeugen

**What to build:** Alle Spec-Ordner tragen ihre Nummer laut Tabelle der Spec (0001–0015) und haben vollständige Kopfzeilen. Eindeutig abgeschlossene Specs stehen auf `resolved` mit Abschlussdatum. Alle Querverweise funktionieren, `.scratch/INDEX.md` ist erzeugt, und der Prüfbefehl ist auf dem echten Bestand grün und läuft in der CI.

**Blocked by:** 01 (Tracking-Schema), 02 (INDEX-Generator und Prüfbefehl)

**Status:** resolved

- [x] Alle 15 Ordner per `git mv` umbenannt (Git erkennt Umbenennungen)
- [x] Kopfzeilen in allen Specs: Created aus dem ersten Commit, Closed aus dem abschließenden Commit, Priority für offene Specs (0008 P2, 0009 P1, 0010 P1, 0011 P3, 0012 P1, 0013 P2, 0014 P2, 0015 P2), Depends-on (0013 → 0009, 0012; 0010 → 0011; 0014 → 0010)
- [x] Für `0002-phpunit-cli-bootstrap` gibt es eine kurze Spec mit Kopfzeilen (Inhalt aus dem vorhandenen Ticket)
- [x] `done` und veraltete Spec-Status ersetzt: 0001, 0003, 0004, 0006, 0007 → resolved (Tickets entsprechend); 0002 und 0005 bleiben bis Ticket 04 offen
- [x] Querverweise in `.scratch/`, `docs/`, `AGENTS.md`, `CODING_STANDARDS.md` und im Projekt-Skill angepasst; `grep` findet keine alten Ordnernamen mehr als Pfad
- [x] `INDEX.md` erzeugt; Prüfbefehl grün; CI-Schritt aktiv
