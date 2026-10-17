# 01: Agent worktrees start from the local HEAD

**What to build:** A checked-in `.claude/settings.json` sets `"worktree": {"baseRef": "head"}`, so agent worktrees
branch from the locally checked-out branch instead of `origin/HEAD`. `docs/agents/git-workflow.md` replaces the
`origin/HEAD` paragraph: the orchestrator checks out the integration branch in the main checkout before starting
implementers, and the reset check stays as a fallback.

**Blocked by:** None (can start immediately)

**Status:** resolved

- [x] `.claude/settings.json` with `worktree.baseRef: "head"`
- [x] `docs/agents/git-workflow.md` updated (base rule, orchestrator checkout, reset fallback)
- [x] Manual check recorded: an agent worktree started while a non-default branch is checked out has that branch's
      tip as base
- [x] `Rot vor Fix:` line recorded (`entfällt – <reason>` is fine for pure tooling)
- [x] `bash scripts/test.sh` green

## Comments

### 2026-10-18 – implementer

Rot vor Fix: entfällt – pure tooling/docs (a settings file and a doc paragraph); there is no automated seam, the
spec asks for a manual check instead.

- Base of this implementer's own worktree (evidence for the problem): it started at `027fd0c9` (= `origin/staging`),
  and `git merge-base --is-ancestor agent/0024-agent-loop-follow-ups HEAD` failed (exit 1). The reset
  `git reset --hard agent/0024-agent-loop-follow-ups` (to `7eb64797`) was needed.
- The setting is real: the installed Claude Code binary (`~/.local/share/claude/versions/2.1.296`) contains the
  settings schema entry `worktree.baseRef: enum ["fresh", "head"]`, described as "Which ref new worktrees branch
  from. 'fresh' (default) branches from origin/<default-branch> for a clean tree. 'head' branches from your current
  local HEAD so unpushed commits and feature-branch state are present. Applies to --worktree, EnterWorktree, and
  agent isolation." The `EnterWorktree` tool description in the same binary names the same setting.
- `.claude/settings.json` is not git-ignored (`git check-ignore` exit 1), so it is checked in.
- Manual end-to-end check **open**: it needs a new Claude Code session (project settings are read at start) that
  starts an agent worktree while a non-default branch is checked out in the main checkout. That cannot be done from
  inside this implementer session, whose worktree was created before the setting existed. To verify: check out
  e.g. `agent/0024-agent-loop-follow-ups` in the main checkout, start a fresh session, start a worktree-isolated
  agent and compare its `git log -1` with that branch's tip.

### 2026-10-18 – review fixes

Manual check (orchestrator evidence plus the review-fix worktree):

- The worktrees for 01, 02 and 05 were created before `.claude/settings.json` existed and started at the stale
  `027fd0c9` (`origin/staging`); all three needed the reset onto `agent/0024-agent-loop-follow-ups`.
- The worktrees for 03 and 04 were created after 01 was merged, while the main checkout had the non-default
  integration branch `agent/0024-agent-loop-follow-ups` checked out. Both started at its tip without a reset.
- The review-fix worktree (`worktree-agent-a49571d211c324466`), created the same way, started at `5f6189ad`, the
  tip of `agent/0024-agent-loop-follow-ups` checked out in the main checkout; `git merge-base --is-ancestor
  agent/0024-agent-loop-follow-ups HEAD` exit 0, no reset.

`bash scripts/test.sh` green: the main-checkout runs after the merges of 02 (ticket 02: exit 0, 8 checks and 8 smokes
OK) and of 04, which includes 03 (ticket 04: exit 0 after `npm ci`, all checks and smokes OK), both contain 01.
