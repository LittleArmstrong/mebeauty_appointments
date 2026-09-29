# AGENTS.md

## Git Safety

### Commits and Pushes

* NEVER create a git commit unless the user explicitly asks for a commit.
* NEVER push to any remote unless the user explicitly asks for a push.
* NEVER merge branches unless explicitly requested.
* NEVER force-push.
* NEVER amend, reset, rebase, cherry-pick, or otherwise rewrite git history unless explicitly requested.
* The user must always make the final decision about committing and pushing changes.
* You MAY suggest a commit message, but MUST NOT execute `git commit` without explicit user approval.

### Existing User Changes

Before modifying any files:

```bash
git status
git branch --show-current
git diff
git diff --cached
```

* NEVER overwrite, discard, reset, clean, stash, or modify existing user changes.
* If the working tree contains uncommitted user changes that are relevant or ambiguous, STOP and ask the user how to proceed.
* Do not assume that existing uncommitted changes belong to the current task.
* NEVER use `git reset --hard` or `git clean` unless explicitly requested.

### Branches

* Never make feature changes directly on `main` or `master`.
* Before implementing a new task, create or use a dedicated task branch.
* Prefer:

```bash
git checkout -b agent/<short-task-name>
```

* If the current branch is `main` or `master`, create a task branch before editing.
* If the repository already contains unrelated work, do not switch branches without confirming with the user.

### Git Inspection

The following commands are always allowed without confirmation:

```bash
git status
git branch --show-current
git diff
git diff --cached
git diff --stat
git log
git remote -v
```

---

## Project

This repository is `mebeauty_appointments`, a fork of Easy!Appointments 1.6.0 based on CodeIgniter 3.1.11.

Repository-specific code and conventions take precedence over generic Easy!Appointments or CodeIgniter documentation.

For repository-specific implementation questions, use the `easyappointments-ci3` skill.

### Source of Truth

When sources disagree, use this priority:

1. Actual repository code
2. Repository codebase analysis / API references
3. Official Easy!Appointments / CodeIgniter documentation
4. GitHub README and other generic documentation

Never assume that generic CodeIgniter or Easy!Appointments behavior applies when the repository implements an `EA_*` override.

---

## Before Changing Code

Before implementing a change:

1. Inspect the relevant existing implementation.
2. Search for existing patterns and similar functionality.
3. Identify the affected controller/model/service/library/view/migration.
4. Check relevant tests.
5. Check the `easyappointments-ci3` skill and repository references when applicable.
6. Explain the planned approach before making substantial changes.

Prefer:

* Existing project conventions
* Existing services over new business logic in controllers
* Existing `EA_*` classes over generic CI3 classes
* Existing models and helpers
* Existing API patterns
* Existing migration conventions

Do NOT:

* Introduce unnecessary abstractions.
* Introduce new dependencies unless necessary.
* Refactor unrelated code.
* Rename or restructure unrelated files.
* Modify generated files unless required.
* Change configuration or secrets without explicit approval.

---

## Scope Control

Only modify files necessary for the requested task.

If you discover a separate bug or improvement:

* Do not silently fix it.
* Mention it to the user.
* Ask whether it should be handled separately.

Avoid unrelated cleanup during feature work.

---

## Database Changes

For database schema changes:

* Use the project's existing migration system.
* Follow the existing numbered migration convention.
* Inspect recent migrations before creating a new one.
* Never modify the database schema manually when a migration is appropriate.
* Do not modify existing migrations unless explicitly requested.

---

## API Changes

For API v1 changes:

* Follow the existing `Api` library conventions.
* Follow existing REST controller patterns.
* Preserve bearer-token authentication.
* Check the repository's OpenAPI definition when relevant.
* Update API documentation/specification when required by the change.

---

## Security

Treat the following as sensitive:

* API keys
* Access tokens
* Passwords
* OAuth credentials
* `.env` files
* Database credentials
* Private keys
* Session secrets

NEVER commit or expose secrets.

If a task requires handling credentials, ask the user when the correct secure approach is unclear.

---

## Validation

After making changes:

1. Inspect the diff.
2. Run the most relevant tests.
3. Run relevant linters/static analysis.
4. Run relevant build/check commands.
5. Check for accidental modifications.
6. Report exactly what was executed and the result.

Do not claim a test passed unless it was actually executed.

If validation fails:

* Investigate failures caused by the current changes.
* Fix them when appropriate.
* Re-run validation.
* Clearly report unresolved failures.

---

## Completion

Before finishing a task, show:

```bash
git status
git diff --stat
git diff
```

Then report:

* Summary of changes
* Files changed
* Tests/checks executed
* Test/check results
* Remaining issues
* Suggested next step
* Optional suggested commit message

STOP after reporting the result.

Do not commit.
Do not push.
Do not merge.

The user decides what happens next.

---

## Agent Session Traceability

For completed development sessions, export the OpenCode session when available:

```bash
opencode export
```

Create/update:

```text
docs/agent-sessions/<date>-session.md
```

The summary should contain:

* Goal
* Files changed
* Important implementation decisions
* Commands executed
* Validation results
* Remaining issues
* Suggested next step

Do not commit or push the session summary.

---

## Agent Behavior

* Prefer small, reviewable changes.
* Ask when requirements are ambiguous.
* Do not guess important requirements.
* Clearly distinguish verified facts from assumptions.
* Do not over-engineer.
* Do not perform autonomous Git history changes.
* Keep the user in control of commits, pushes, merges, and releases.

---

## Agent skills

### Issue tracker

Issues and specs are tracked as local markdown files under `.scratch/<feature>/`. See `docs/agents/issue-tracker.md`.

### Triage labels

Uses the five canonical triage labels unchanged. See `docs/agents/triage-labels.md`.

### Domain docs

Single-context layout: one `GLOSSARY.md` + `docs/adr/` at the repo root. See `docs/agents/domain.md`.
