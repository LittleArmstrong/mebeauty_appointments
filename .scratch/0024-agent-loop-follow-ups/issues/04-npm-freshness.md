# 04: Report and install missing npm packages

**What to build:** The post-checkout and post-merge hooks print one line when `package-lock.json` is newer than
`node_modules/.package-lock.json` and nothing otherwise; they never install. `scripts/test.sh` runs `npm ci` itself in
that case (main checkout only; a worktree links the main checkout's `node_modules`).

**Blocked by:** 03 (both edit the hooks and `scripts/test.sh`)

**Status:** ready-for-agent

- [x] Hooks print the npm line only when the lockfile is newer
- [x] `scripts/test.sh` runs `npm ci` when the lockfile is newer (main checkout)
- [x] Manual check recorded: checkout across a `package-lock.json` change prints the line, `scripts/test.sh` installs
- [x] `Rot vor Fix:` line recorded (`entfällt – <reason>` is fine for pure tooling)
- [x] `bash scripts/test.sh` green

## Comments

- Rot vor Fix: in a throwaway clone (`git clone --shared` of the worktree, `node_modules/.package-lock.json` dated
  2020, `package-lock.json` touched), the unchanged `post-checkout a b 1` and `post-merge` printed nothing and
  exited 0. After the change both print
  `post-checkout: package-lock.json ist neuer als node_modules – npm ci fällig (scripts/test.sh installiert selbst)`
  (`post-merge: …` likewise), exit 0; with a fresh `.package-lock.json` and on a file checkout (`a b 0`) they print
  nothing; without `node_modules` they print the line. The check lives in `scripts/lib/npm-deps.sh`
  (`npm_lock_stale`, `npm_lock_notice`), used by both hooks and `scripts/test.sh`.
- Manual checkout check (agent, same clone, hooks via `git -c core.hooksPath=…`): after `npm ci` a real
  `git checkout lock-change` across a commit that changes `package-lock.json` printed the npm line, exit 0. The npm
  block of `scripts/test.sh` (extracted by `sed` and run in the clone) printed `== npm ci: package-lock.json ist
  neuer als node_modules`, ran `npm ci` (`added 671 packages`), exit 0; `node_modules/.package-lock.json` went from
  01:18:17.81 to 01:18:42.43, `package-lock.json` stayed at 01:18:17.85 (npm ci does not touch it, so no loop).
  Afterwards both hooks were silent. The full `scripts/test.sh` was not run in the clone (its stack would clash
  with the main checkout's ports). Clone deleted; the main checkout's `node_modules` was not touched.
- In a worktree the hooks stay silent and `scripts/test.sh` never runs `npm ci`: the worktree's `node_modules` is a
  link to the main checkout's, and its lockfile's mtime is the worktree's creation time.
- Worktree link fix (found in 03): `scripts/test.sh` now replaces a real `node_modules` directory without
  `.package-lock.json` (e.g. the `node_modules/.cache/prettier` the pre-commit hook creates) with the link to the
  main checkout's `node_modules`. Check: `mkdir -p node_modules/.cache/prettier` in this worktree, then
  `bash scripts/test.sh` → JS build of 85 sources, all checks and smokes OK, exit 0, `node_modules ->
  ../../../node_modules`. Before the fix this state aborted with exit 2 (`veraltetes .min.js, aber node_modules
  fehlt`, ticket 03 comments).
- Note: the main checkout's `package-lock.json` (2026-10-17 23:31:41) is currently newer than its
  `node_modules/.package-lock.json` (23:31:05), so the first `scripts/test.sh` run there after the merge runs
  `npm ci`.
- `bash scripts/test.sh` in the worktree: exit 0 (see above).
