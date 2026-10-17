# 02: Smokes run against the worktree

**What to build:** In a worktree, `bash scripts/test.sh` runs every smoke against the worktree's own code: `BASE_URL`
points to the worktree below nginx, the smoke fixtures run in `php-fpm` with the worktree as working directory, and
the worktree's application generates its URLs for that path. A green worktree run exits 0; exit 3 and its mentions
in `AGENTS.md` and the docs go away. The database stays shared; smokes keep using only their own fixtures.

**Blocked by:** None (can start immediately)

**Status:** ready-for-agent

- [x] Fixtures run in the checkout being tested (built on `scripts/lib/stack.sh`)
- [x] `scripts/test.sh` runs the smokes in a worktree with the worktree's `BASE_URL`; green run exits 0
- [x] Exit 3 removed from `scripts/test.sh`, `AGENTS.md` and the docs
- [x] Manual check recorded: a smoke that only passes with the worktree's change passes in the worktree run
- [x] Manual check recorded: no `smoke-…` customer left, the user's customers unchanged
- [x] `Rot vor Fix:` line recorded (`entfällt – <reason>` is fine for pure tooling)
- [x] `bash scripts/test.sh` green in the worktree and in the main checkout

## Comments

Implementation (2026-10-18):

- `scripts/lib/stack.sh` exports `STACK_URL_PATH` (`/.claude/worktrees/<name>`, empty in the main checkout).
- `tests/smoke/lib.sh` sources the stack library (from its own directory, cwd unchanged), defaults `BASE` to
  `http://localhost$STACK_URL_PATH` and runs PHP through the new `smoke_php` helper (`stack_exec … php-fpm`, i.e.
  `-w` = the checkout's directory); `fixtures` uses it. `language-selector-smoke.sh` and `email-logo-smoke.sh` called
  `docker compose exec` themselves and now source `lib.sh` (their duplicated `BASE`/`fail`/`ok` are gone; the logo
  part goes to `$TMP/logo.png`, cleaned up by `smoke_cleanup`).
- `scripts/test.sh` exports `BASE_URL` (default `http://localhost$STACK_URL_PATH`), sets it as `Config::BASE_URL`
  in the worktree's `config.php` copy on every run (also fixes copies made by earlier runs; web requests derive
  their base URL from the request, `application/config/config.php:33`, so only CLI code needs it), waits for the
  site at that URL after the worktree setup, runs the purge and every smoke in the checkout under test, and exits
  0/1/2 only (exit 3 removed). `AGENTS.md`, `docs/agents/validation-and-completion.md`, `docs/docker.md` updated.

Manual check "only passes with the worktree's change" (temporary, reverted before commit): a marker
`<!-- worktree-proof -->` in `application/views/pages/booking.php`, a fixture command `worktree-proof` in
`tests/smoke/fixtures.php` printing `config_item('base_url')`, and a smoke `zz-worktree-proof-smoke.sh` asserting
both. `bash scripts/test.sh` in this worktree:

```
== smoke zz-worktree-proof
BASE=http://localhost/.claude/worktrees/agent-af17d26fe7c2db124
OK   booking page carries the worktree-only marker
OK   fixture command of the worktree runs, CLI base_url = http://localhost/.claude/worktrees/agent-af17d26fe7c2db124
```

All 8 checks and all 9 smokes OK in that run. The same smoke against the main checkout
(`BASE_URL=http://localhost bash tests/smoke/zz-worktree-proof-smoke.sh`) exits 1 with
`FAIL booking page carries the worktree-only marker`, and the old fixture path (`docker compose exec -T php-fpm …
fixtures.php worktree-proof`, main checkout) exits 2 with the usage line: the command does not exist there.

Manual check "shared database" (query over `ea_users`/`ea_roles`/`ea_appointments`, before and after the run):

```
                                   before                                after
smoke users (email smoke-%)        0                                     0
other customers (count, md5)       2  45b45cfdae10bc87f934db93bcafdcde   2  45b45cfdae10bc87f934db93bcafdcde
their appointments (count, md5)    2  c8010e956a9bdab6448fd026ff8cf497   2  c8010e956a9bdab6448fd026ff8cf497
```

The md5 covers id, email/start and `update_datetime` of every row. Note: the leftover purge and the
`language-selector` smoke (temporary setting change) act on the shared database, so parallel smoke runs of several
worktrees can still interfere (`.scratch/IDEAS.md`, separate stack per worktree).

Rot vor Fix: entfällt – pure tooling; before the change `scripts/test.sh` skipped every smoke in a worktree
(`SKIP  smokes`, exit 3), the proof smoke above is the worktree-only evidence.

Validation: `bash scripts/test.sh` in the worktree exit 0 (8 checks, 8 smokes OK). The main-checkout run follows
after the merge (orchestrator).

Main checkout after the fast-forward merge into `agent/0024-agent-loop-follow-ups` (23bafcd5): `bash scripts/test.sh`
exit 0, all 8 checks and all 8 smokes OK (BASE_URL `http://localhost`).
