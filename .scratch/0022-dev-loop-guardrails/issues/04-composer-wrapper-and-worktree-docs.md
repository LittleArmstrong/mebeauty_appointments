# 04: Composer wrapper for main checkout and worktrees, worktree base documented

**What to build:** A script that runs `composer <args>` in `php-fpm` with the working directory of the current
checkout (main or worktree), built on the existing stack library. Docs that show
`docker compose exec -T php-fpm composer …` for repository commands point to it. The git-workflow doc states that
agent worktrees branch from `origin/HEAD`, which must point to `staging`.

**Blocked by:** 03 (both edit `docs/agents/issue-tracker.md`)

**Status:** ready-for-agent

- [ ] Wrapper script, same behaviour in main checkout and worktree
- [ ] Docs updated (`AGENTS.md`, `docs/agents/issue-tracker.md`, `docs/docker.md` where they show composer commands)
- [ ] Git-workflow doc: worktree base and `git remote set-head origin staging`
- [ ] Manual check recorded: `scratch:index` via the wrapper in a worktree changes only the worktree
- [ ] `Rot vor Fix:` line recorded (`entfällt – <reason>` is fine for pure tooling)
- [ ] `bash scripts/test.sh` green

## Comments
