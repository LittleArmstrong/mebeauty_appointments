# 04: Composer wrapper for main checkout and worktrees, worktree base documented, nginx logs visible

**What to build:** A script that runs `composer <args>` in `php-fpm` with the working directory of the current
checkout (main or worktree), built on the existing stack library. Docs that show
`docker compose exec -T php-fpm composer …` for repository commands point to it. The git-workflow doc states that
agent worktrees branch from `origin/HEAD`, which must point to `staging`. nginx writes its access and error logs to
the image's default paths, so `docker compose logs nginx` shows the requests (no request bodies).

**Blocked by:** 03 (both edit `docs/agents/issue-tracker.md`)

**Status:** ready-for-agent

- [ ] Wrapper script, same behaviour in main checkout and worktree
- [ ] Docs updated (`AGENTS.md`, `docs/agents/issue-tracker.md`, `docs/docker.md` where they show composer commands)
- [ ] Git-workflow doc: worktree base and `git remote set-head origin staging`
- [ ] nginx logs on the default paths; a booking request appears in `docker compose logs nginx`
- [ ] Manual check recorded: `scratch:index` via the wrapper in a worktree changes only the worktree
- [ ] `Rot vor Fix:` line recorded (`entfällt – <reason>` is fine for pure tooling)
- [ ] `bash scripts/test.sh` green

## Comments
