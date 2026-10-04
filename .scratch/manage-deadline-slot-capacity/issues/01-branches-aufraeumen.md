# 01: Branches aufräumen

**What to build:** Das Repo enthält nach dem Aufräumen lokal nur noch `main`; alle bereits in `main` gemergten `ticket/*`- und `integration/*`-Branches sowie der verwaiste `agent/google-calendar-push-sync`-Branch sind lokal gelöscht. Das Remote bleibt unangetastet.

**Blocked by:** None (can start immediately)

**Status:** resolved

- [ ] `git branch` listet nur noch `main` (keine `ticket/*`, `integration/*`, `agent/google-calendar-push-sync`)
- [ ] Remote-Branches unverändert (kein `push`, kein Löschen auf origin)
