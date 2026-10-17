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

Exit 3 in a worktree under `.claude/worktrees/`: everything but the smokes is green; they run in the
main checkout after the merge.

Syntax and format of staged files are checked by the pre-commit hook. Theme layout and
texts are checked visually by the user, not by a smoke test.

Single checks run inside the `php-fpm` container (host `php` is a ddev shim) via
`bash scripts/composer.sh test` etc., in the current checkout, main or worktree; full list in `docs/docker.md`.
A plain `docker compose exec -T php-fpm composer …` never runs in a worktree: it works in the main checkout.

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
After (2), merge the branch into `staging`; the user verifies there. Otherwise ask first.
Never push. See `docs/agents/git-workflow.md`.

## Load when relevant

- `CODING_STANDARDS.md` — review rules (judgement calls the checks cannot enforce)
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
