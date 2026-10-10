# AGENTS.md

mebeauty_appointments — a fork of Easy!Appointments 1.6.0 based on CodeIgniter 3.1.11.
Repository code and `EA_*` overrides take precedence over generic Easy!Appointments or
CodeIgniter documentation. For repository-specific implementation questions, use the
`easyappointments-ci3` skill.

## Validate every change

```bash
docker compose exec -T php-fpm composer test          # PHPUnit suite
docker compose exec -T php-fpm php -l <file>          # PHP syntax, per changed file
node --check <file>                                    # JS syntax, per changed file
bash tests/smoke/mebeauty-layout-smoke.sh             # public-page HTTP smoke (site must be up)
```

The smoke test needs the settings `theme=mebeauty` and `company_name=MeBeauty` in the
site's database (not migration defaults); otherwise `booking-theme`/`booking-title` fail.

All PHP/Composer commands run inside the `php-fpm` container (host `php` is a ddev shim).
Full command list: `docs/docker.md`.

## Before changing code

- Check `.scratch/` for a spec; `Status: resolved` = already committed, don't redo it.
- Read `docs/agents/development-conventions.md` before substantial changes.
- Before visual/layout measurement or Playwright work, read `docs/visual-measurement.md`.

## Commits

Two authorized commit checkpoints: (1) after a spec + ticket is created in `.scratch/`
and is triage-ready, (2) after `/implement` or `/implement-spec` passes validation.
Otherwise ask first. Never push or merge. See `docs/agents/git-workflow.md`.

## Load when relevant

- `docs/agents/git-workflow.md` — branches, existing user changes, inspection, commit discipline
- `docs/agents/development-conventions.md` — code conventions, DB migrations, API v1, security, scope
- `docs/agents/validation-and-completion.md` — full validation and completion report
- `docs/agents/session-traceability.md` — session export and summary

## Agent skills

### Issue tracker

Issues and specs are tracked as local markdown files under `.scratch/<feature>/`. See `docs/agents/issue-tracker.md`.

### Triage labels

Uses the five canonical triage labels unchanged. See `docs/agents/triage-labels.md`.

### Domain docs

Single-context layout: one `GLOSSARY.md` + `docs/adr/` at the repo root. See `docs/agents/domain.md`.
