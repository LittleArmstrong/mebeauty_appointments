# 01: Agent worktrees start from the local HEAD

**What to build:** A checked-in `.claude/settings.json` sets `"worktree": {"baseRef": "head"}`, so agent worktrees
branch from the locally checked-out branch instead of `origin/HEAD`. `docs/agents/git-workflow.md` replaces the
`origin/HEAD` paragraph: the orchestrator checks out the integration branch in the main checkout before starting
implementers, and the reset check stays as a fallback.

**Blocked by:** None (can start immediately)

**Status:** ready-for-agent

- [ ] `.claude/settings.json` with `worktree.baseRef: "head"`
- [ ] `docs/agents/git-workflow.md` updated (base rule, orchestrator checkout, reset fallback)
- [ ] Manual check recorded: an agent worktree started while a non-default branch is checked out has that branch's
      tip as base
- [ ] `Rot vor Fix:` line recorded (`entfällt – <reason>` is fine for pure tooling)
- [ ] `bash scripts/test.sh` green

## Comments
