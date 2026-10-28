# 06: NOTICE for brand assets

**What to build:** A root `NOTICE` stating that the code is GPL-3.0 while the MeBeauty name, logos
(`assets/img/mebeauty/`) and MeBeauty texts are not licensed under the GPL; the README links to it.

**Blocked by:** –

**Status:** resolved

- [x] `NOTICE` at the repository root
- [x] README links to it
- [x] `Rot vor Fix:` line recorded
- [x] `bash scripts/test.sh` green

## Comments

- 2026-10-28 (implementer, worktree `agent-ac653be2b8d4b5b32`, branch `agent/0025-06-notice`): root `NOTICE`
  (English) states that the code is GPL-3.0 (`LICENSE`, based on Easy!Appointments, `UPSTREAM.md`) and that the
  MeBeauty name, `assets/img/mebeauty/` (logo, hero, app icon, social icons), `assets/img/favicon.ico` (replaced by
  the MeBeauty favicon in `4f7aa26b`), other MeBeauty brand assets and the MeBeauty texts
  (`application/views/themes/mebeauty/`, `site_*` language keys) are not licensed under the GPL, all rights
  reserved by their owner. `assets/img/logo.png` and `social-card.png` are the upstream files and are not listed.
  The README's License section links to `NOTICE` in one line. The Skill `tdd` does not apply: docs only.
- Rot vor Fix: entfällt – docs only
- Validation: `bash scripts/test.sh` aborted with exit 2 before any check ran: `docker compose up -d` cannot start
  `mebeauty_appointments-mailpit-1` because host port 1025 is held by another project's container
  (`platform-mailpit-1`). Single checks in the worktree: `check:scratch` OK, `check:assets` OK. The test.sh item
  stays open until a full run is green.

**2026-10-28, orchestrator:** `bash scripts/test.sh` green in the main checkout on merge commit `74984699` (integration branch incl. review fixes): PHPUnit, all checks and all smokes OK.
