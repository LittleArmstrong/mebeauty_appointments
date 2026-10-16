# 01: Fresh JavaScript after a branch switch, no stale browser cache

**What to build:** After `git checkout` the page serves the checked-out branch's JavaScript, and a normal reload
picks it up. The staleness check of the test script moves to the shared shell library. A `post-checkout` hook uses
it to rebuild stale scripts (quiet when nothing is stale, warns but never blocks on failure). Asset URLs carry a
per-file token from the served file's modification time, with the configured token as fallback, decided in a
fork-owned rule that the upstream asset helper calls.

**Blocked by:** None (can start immediately)

**Status:** ready-for-agent

- [ ] Staleness check in the shared shell library, test script uses it unchanged in behaviour
- [ ] `post-checkout` hook: branch checkouts only, rebuild only when stale and `node_modules` exists, one line on
      rebuild, warning only on failure
- [ ] Per-file cache-busting token in a fork-owned rule; upstream asset helper only calls it
- [ ] PHPUnit tests for the token rule (existing file, missing file, minified and non-minified path)
- [ ] Manual check recorded: checkout `staging` ↔ branch with a JS change → rebuild line, new code on normal reload
- [ ] `Rot vor Fix:` line recorded
- [ ] `bash scripts/test.sh` green

## Comments
