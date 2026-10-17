# 02: Smokes run against the worktree

**What to build:** In a worktree, `bash scripts/test.sh` runs every smoke against the worktree's own code: `BASE_URL`
points to the worktree below nginx, the smoke fixtures run in `php-fpm` with the worktree as working directory, and
the worktree's application generates its URLs for that path. A green worktree run exits 0; exit 3 and its mentions
in `AGENTS.md` and the docs go away. The database stays shared; smokes keep using only their own fixtures.

**Blocked by:** None (can start immediately)

**Status:** ready-for-agent

- [ ] Fixtures run in the checkout being tested (built on `scripts/lib/stack.sh`)
- [ ] `scripts/test.sh` runs the smokes in a worktree with the worktree's `BASE_URL`; green run exits 0
- [ ] Exit 3 removed from `scripts/test.sh`, `AGENTS.md` and the docs
- [ ] Manual check recorded: a smoke that only passes with the worktree's change passes in the worktree run
- [ ] Manual check recorded: no `smoke-…` customer left, the user's customers unchanged
- [ ] `Rot vor Fix:` line recorded (`entfällt – <reason>` is fine for pure tooling)
- [ ] `bash scripts/test.sh` green in the worktree and in the main checkout

## Comments
