# 04: Ein Befehl für die gesamte Validierung

**What to build:** `bash scripts/test.sh` fährt den Docker-Stack hoch, wartet auf MySQL und die Seite, prüft die Voraussetzungen und führt alle Prüfungen und Smoke-Tests aus. Am Ende steht eine Zusammenfassung grün/rot. `AGENTS.md` nennt den Befehl als Standard-Validierung.

**Blocked by:** 02

**Status:** ready-for-agent

- [ ] `docker compose up -d`, Warten auf MySQL (max. 60 s) und `http://localhost/`; fehlende `config.php` oder `vendor/autoload.php` brechen mit klarer Meldung ab
- [ ] Führt aus: `composer test`, `check:format`, `check:migrations`, `check:assets`, `check:scratch`, `check:views`, alle `tests/smoke/*-smoke.sh`; läuft nach einem Rot weiter
- [ ] Zusammenfassung je Prüfung; Exit 1 bei mindestens einem Rot
- [ ] `AGENTS.md` (Validate every change), `docs/agents/validation-and-completion.md` (Voraussetzungen) und `docs/docker.md` verweisen auf das Skript
- [ ] Probe: Lauf mit laufendem und mit gestopptem Stack
