# 01: Projekt-Skill nach `.claude/skills` verschieben, Skills-Lock entfernen

**What to build:** Der projektspezifische Skill `easyappointments-ci3` liegt versioniert unter `.claude/skills/easyappointments-ci3/` und wird von Claude Code und OpenCode automatisch geladen. Die funktionslose `skills-lock.json` ist entfernt. Inhaltlich ändert sich am Skill nichts.

**Blocked by:** None (can start immediately)

**Status:** resolved

- [x] Reiner Verschiebe-Commit (`git mv`); Git erkennt alle Dateien als Umbenennung
- [x] Unter `.claude/skills/easyappointments-ci3` sind 435 Dateien versioniert, unter `.opencode` keine mehr
- [x] `skills-lock.json` ist aus dem Repo entfernt; lokale, nicht versionierte `.opencode`-Dateien bleiben unberührt
- [x] Claude Code und OpenCode listen den Skill im Projekt
- [x] AGENTS.md nennt den Ablageort

## Comments

- OpenCode 1.18.35 (`opencode debug skill`) listet den Skill unter `.claude/skills/easyappointments-ci3/SKILL.md`. Claude Code lädt Projekt-Skills beim Sitzungsstart; Sichtbarkeit in der nächsten Sitzung bestätigen.
