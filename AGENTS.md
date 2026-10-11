# AGENTS.md

mebeauty_appointments — a fork of Easy!Appointments 1.6.0 based on CodeIgniter 3.1.11.
Repository code and `EA_*` overrides take precedence over generic Easy!Appointments or
CodeIgniter documentation. For repository-specific implementation questions, use the
`easyappointments-ci3` skill
(`.claude/skills/easyappointments-ci3/`, loaded by Claude Code and OpenCode).

## Validate every change

```bash
bash scripts/test.sh    # starts the stack, waits for MySQL + site, runs PHPUnit, all checks, all smokes
```

Syntax and format of staged files are checked by the pre-commit hook. The smoke tests need
the settings `theme=mebeauty` and `company_name=MeBeauty` in the site's database (not
migration defaults); otherwise `booking-theme`/`booking-title` fail.

Single checks run inside the `php-fpm` container (host `php` is a ddev shim):
`docker compose exec -T php-fpm composer test` etc., full list in `docs/docker.md`.

## Before changing code

- Check `.scratch/` for a spec; `Status: resolved` = already committed, don't redo it.
- Read `docs/agents/development-conventions.md` before substantial changes.
- Before visual/layout measurement or Playwright work, read `docs/visual-measurement.md`.

## Durable knowledge

Sessions and handoffs are disposable. Anything worth keeping lands in a spec, a ticket
comment, an ADR or `docs/` before the session ends.

## Commits

Two authorized commit checkpoints: (1) after a spec + ticket is created in `.scratch/`
and is triage-ready, (2) after `/implement` or `/implement-spec` passes validation.
Otherwise ask first. Never push or merge. See `docs/agents/git-workflow.md`.

## Load when relevant

- `docs/agents/git-workflow.md` — branches, existing user changes, inspection, commit discipline
- `docs/agents/development-conventions.md` — code conventions, DB migrations, API v1, security, scope
- `docs/agents/validation-and-completion.md` — full validation and completion report

## Agent skills

### Issue tracker

Issues and specs are tracked as local markdown files under `.scratch/<NNNN>-<feature>/`; overview in the
generated `.scratch/INDEX.md` (`composer scratch:index`, validated by `composer check:scratch`).
See `docs/agents/issue-tracker.md`.

### Triage labels

Uses the five canonical triage labels unchanged. See `docs/agents/triage-labels.md`.

### Domain docs

Single-context layout: one `GLOSSARY.md` + `docs/adr/` at the repo root. See `docs/agents/domain.md`.
