# 05: Projekt-Skill kürzen und an den Repo-Stand anpassen

**What to build:** Der Projekt-Skill enthält nur noch `SKILL.md` und die offline gespiegelte CI3-Dokumentation. Generierte Analysen, Konflikt-Report, API-Mischreferenz und README-Kopie sind entfernt. `SKILL.md` macht korrekte Aussagen über das Repo und verweist auf die Fork-Konventionen.

**Blocked by:** 01 (Projekt-Skill nach `.claude/skills` verschieben)

**Status:** ready-for-agent

- [ ] Entfernt: generierte Code-Analyse, Konflikt-Report, gemischte API-Referenz, GitHub-/README-Kopie
- [ ] `SKILL.md`: Quellen-Tabelle, Navigation und „Known Discrepancies“ ohne Verweise auf entfernte Teile; der Code ist die Quelle der Wahrheit; Hinweis auf die Neuerzeugung mit Skill Seekers
- [ ] Korrigiert: „Service Layer/18 service classes“ → Libraries; Migrationsanzahl konsistent
- [ ] Neuer Abschnitt mit Verweisen auf AGENTS.md, die Agenten-Doku, das Glossar und die ADRs (u. a. Migrationsnummern, Site-Chrome pro Theme, PHP-Versionen)
- [ ] Keine absoluten lokalen Pfade mehr im Skill
- [ ] Claude Code und OpenCode laden den Skill weiterhin
