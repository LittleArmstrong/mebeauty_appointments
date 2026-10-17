# Git Workflow

## Commit checkpoints

The repository uses explicit phase commits. The agent MAY commit at these workflow
checkpoints:

1. After creating or updating a feature specification and ticket in `.scratch/`,
   and only once the ticket is coherent and triage-ready (it carries a `Status:`
   line set, not a half-finished WIP state).
2. After completing `/implement` or `/implement-spec`, including the required
   validation and code review.

After checkpoint 2 the agent merges the task or integration branch into `staging`
(fast-forward when possible, otherwise a merge commit; regenerate `.scratch/INDEX.md`
on a conflict there). The user verifies on `staging`, and the push stays with the user.

For any other commit, ask the user for approval first.

Before creating a commit:

- Inspect `git status`.
- Inspect `git diff`.
- Inspect `git diff --cached`.
- Stage only files belonging to the current workflow step.
- Do not include unrelated user changes.
- Use a clear, conventional commit message, written in English.

The agent MUST NOT:

- Push to any remote unless the user explicitly asks for a push.
- Merge branches other than the `staging` merge after checkpoint 2, unless explicitly requested.
- Force-push.
- Amend, reset, rebase, cherry-pick, or otherwise rewrite git history unless explicitly requested.
- Commit unrelated or pre-existing user changes.

The user always makes the final decision about pushing and merging.

## Existing user changes

Before modifying any files:

```bash
git status
git branch --show-current
git diff
git diff --cached
```

Treat every working-tree change you did not make yourself in this session as the
user's work — including deletions, which look like "missing files" but are often
deliberate. When you find such a change (at the start or mid-task):

1. Identify whose it is: compare against what you changed this session and check the
   handoff/spec for a mention.
2. Leave it untouched and work around it: stage only your own paths
   (`git add <path>`, never `git add -A`/`.`).
3. If it blocks your task or you cannot tell whose it is, ask the user before acting.

Restoring or discarding (`git checkout -- <path>`, `git restore`, `git reset --hard`,
`git clean`, `git stash`) is a user decision — run it only when the user explicitly
asks for that specific path.

## Branches

- `staging` — active development/test branch. Every push auto-deploys to
  `booking-test.mebeauty-koeln.de` via GitHub Actions (`deploy.yml`). Do day-to-day
  work here.
- `main` — production branch. Updated **only via pull request from `staging`**.
  Never commit or push to it directly.

- Never make feature changes directly on `main` or `master`.
- Before implementing a new task, create a dedicated task branch from `staging`:

```bash
git checkout staging && git checkout -b agent/<short-task-name>
```

- Finished task branches are merged into `staging` after checkpoint 2 (see above);
  the user pushes `staging` to trigger the test deploy.
- Release to prod: PR `staging → main` (in the browser), then tag on `main`.
- If the repository already contains unrelated work, do not switch branches without confirming with the user.

## Releases & Tags

The release/versioning workflow (PR `staging → main`, release tags) lives in
`docs/deployment.md` (see "Versionen & Tags"). Agent-relevant invariants:

- `main` is updated only via PR — never commit or push to it directly.
- A tag points to a commit, not a branch, and is not pushed automatically.

## Always-allowed inspection

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
