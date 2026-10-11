# 04: Ein Befehl für die gesamte Validierung

**What to build:** `bash scripts/test.sh` fährt den Docker-Stack hoch, wartet auf MySQL und die Seite, prüft die Voraussetzungen und führt alle Prüfungen und Smoke-Tests aus. Am Ende steht eine Zusammenfassung grün/rot. `AGENTS.md` nennt den Befehl als Standard-Validierung.

**Blocked by:** 02

**Status:** resolved

- [x] `docker compose up -d`, Warten auf MySQL (max. 60 s) und `http://localhost/`; fehlende `config.php` oder `vendor/autoload.php` brechen mit klarer Meldung ab
- [x] Führt aus: `composer test`, `check:format`, `check:migrations`, `check:assets`, `check:scratch`, `check:views`, alle `tests/smoke/*-smoke.sh`; läuft nach einem Rot weiter
- [x] Zusammenfassung je Prüfung; Exit 1 bei mindestens einem Rot
- [x] `AGENTS.md` (Validate every change), `docs/agents/validation-and-completion.md` (Voraussetzungen) und `docs/docker.md` verweisen auf das Skript
- [x] Probe: Lauf mit laufendem und mit gestopptem Stack

## Comments

- Lauf mit laufendem Stack: 10/10 grün in 35 s.
- Lauf nach `docker compose down` (0 Container) mit absichtlich ungeescapter Ausgabe in `site_whatsapp.php`: Stack startet (alle 9 Dienste), `check:views` FAIL mit Datei/Zeile, alle übrigen Prüfungen laufen weiter und sind grün, Exit 1, 32 s. Probeänderung zurückgesetzt.
- Kein Verhaltenswechsel der Anwendung, daher kein Rot-Beleg im Sinne von „Tests must go red“; der FAIL-Pfad des Skripts ist durch die Probe belegt.
