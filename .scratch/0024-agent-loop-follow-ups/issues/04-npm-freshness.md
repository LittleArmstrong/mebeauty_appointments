# 04: Report and install missing npm packages

**What to build:** The post-checkout and post-merge hooks print one line when `package-lock.json` is newer than
`node_modules/.package-lock.json` and nothing otherwise; they never install. `scripts/test.sh` runs `npm ci` itself in
that case (main checkout only; a worktree links the main checkout's `node_modules`).

**Blocked by:** 03 (both edit the hooks and `scripts/test.sh`)

**Status:** ready-for-agent

- [ ] Hooks print the npm line only when the lockfile is newer
- [ ] `scripts/test.sh` runs `npm ci` when the lockfile is newer (main checkout)
- [ ] Manual check recorded: checkout across a `package-lock.json` change prints the line, `scripts/test.sh` installs
- [ ] `Rot vor Fix:` line recorded (`entfällt – <reason>` is fine for pure tooling)
- [ ] `bash scripts/test.sh` green

## Comments
