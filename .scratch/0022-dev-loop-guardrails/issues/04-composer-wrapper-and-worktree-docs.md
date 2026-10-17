# 04: Composer wrapper for main checkout and worktrees, worktree base documented, nginx logs visible

**What to build:** A script that runs `composer <args>` in `php-fpm` with the working directory of the current
checkout (main or worktree), built on the existing stack library. Docs that show
`docker compose exec -T php-fpm composer …` for repository commands point to it. The git-workflow doc states that
agent worktrees branch from `origin/HEAD`, which must point to `staging`. nginx writes its access and error logs to
the image's default paths, so `docker compose logs nginx` shows the requests (no request bodies).

**Blocked by:** 03 (both edit `docs/agents/issue-tracker.md`)

**Status:** resolved

- [x] Wrapper script, same behaviour in main checkout and worktree
- [x] Docs updated (`AGENTS.md`, `docs/agents/issue-tracker.md`, `docs/docker.md` where they show composer commands)
- [x] Git-workflow doc: worktree base and `git remote set-head origin staging`
- [x] nginx logs on the default paths; a booking request appears in `docker compose logs nginx`
- [x] Manual check recorded: `scratch:index` via the wrapper in a worktree changes only the worktree
- [x] `Rot vor Fix:` line recorded (`entfällt – <reason>` is fine for pure tooling)
- [x] `bash scripts/test.sh` green

## Comments

**2026-10-17, implementer (worktree `.claude/worktrees/agent-a7f5169de6fa9234f`):**

Rot vor Fix: entfällt – pure tooling and docs, no automated test (spec, Testing Decisions); the old behaviour
was reproduced by hand instead:
- `docker compose -f <main>/docker-compose.yml exec -T php-fpm pwd` → `/var/www/html` (main checkout), also
  `composer config --absolute vendor-dir` → `/var/www/html/vendor`. A plain `docker compose exec -T php-fpm …`
  from the worktree fails with `service "php-fpm" is not running` (its directory name gives a different Compose
  project).
- `curl http://localhost/?red-evidence-0022-04` → 200, `docker compose logs --since 2m nginx | grep -c
  red-evidence-0022-04` → 0, while `/var/log/nginx/application.access.log` in the container had 1 hit.

Manual checks:
- Wrapper in a worktree changes only the worktree: appended a `stale` line to the worktree's `.scratch/INDEX.md`,
  ran `bash scripts/composer.sh scratch:index` → `INDEX.md erzeugt (23 Specs).`; the worktree's index was
  regenerated (`git status` clean for it again), the main checkout's `.scratch/INDEX.md` kept sha1 `33ff7370…`
  and mtime `1792271785` from before the run. In the main checkout the wrapper uses `STACK_WORKDIR=/var/www/html`,
  the same directory as the old command (checked by reading `scripts/lib/stack.sh`, not run there: the main
  checkout must not be changed before the merge).
- nginx logs: the running nginx mounts the main checkout's config, which this worktree must not change. Checked
  with a temporary container `nginx:1.23.3-alpine` on the stack network (`mebeauty_appointments_default`, port
  8099) with the worktree's `docker/nginx/nginx.conf`. `docker logs` (same container output that
  `docker compose logs nginx` shows) listed `GET /index.php/booking?green-0022-04 … 200`,
  `POST /index.php/booking/get_available_hours … 500` (dummy body without CSRF token) and
  `GET /missing-0022-04.png … 404`; the request body marker `do-not-log` did not appear. Container stopped
  afterwards, the stack's nginx kept running unchanged. After the merge into the main checkout the stack's nginx
  needs `docker compose up -d --force-recreate nginx` to pick up the config (documented in `docs/docker.md`).
- `origin/HEAD`: `git symbolic-ref refs/remotes/origin/HEAD` → `refs/remotes/origin/staging`.
- `bash scripts/test.sh` in the worktree: phpunit and all checks OK, smokes skipped (exit 3, run in the main checkout after the merge).
- 2026-10-17 (agent, review fix): the pre-commit hint for an invalid `.scratch` names
  `bash scripts/composer.sh scratch:index`.
