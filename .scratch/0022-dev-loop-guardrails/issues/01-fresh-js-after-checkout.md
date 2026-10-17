# 01: Fresh JavaScript after a branch switch, no stale browser cache

**What to build:** After `git checkout` the page serves the checked-out branch's JavaScript, and a normal reload
picks it up. The staleness check of the test script moves to the shared shell library. A `post-checkout` hook uses
it to rebuild stale scripts (quiet when nothing is stale, warns but never blocks on failure). Asset URLs carry a
per-file token from the served file's modification time, with the configured token as fallback, decided in a
fork-owned rule that the upstream asset helper calls.

**Blocked by:** None (can start immediately)

**Status:** needs-verification

- [x] Staleness check in the shared shell library, test script uses it unchanged in behaviour
- [x] `post-checkout` hook: branch checkouts only, rebuild only when stale and `node_modules` exists, one line on
      rebuild, warning only on failure
- [x] Per-file cache-busting token in a fork-owned rule; upstream asset helper only calls it
- [x] PHPUnit tests for the token rule (existing file, missing file, minified and non-minified path)
- [ ] Manual check recorded: checkout `staging` ↔ branch with a JS change → rebuild line, new code on normal reload
- [x] `Rot vor Fix:` line recorded
- [x] `bash scripts/test.sh` green

## Comments

- 2026-10-17 (agent, branch `agent/0022-01-fresh-js`): Staleness check moved to `scripts/lib/js-build.sh`
  (`js_stale_scripts`, same find/stat logic); `scripts/test.sh` sources it, behaviour unchanged. New
  `scripts/git-hooks/post-checkout`: exits on file checkouts (`$3 != 1`), silent when `node_modules/.bin/gulp` is
  missing or nothing is stale, otherwise one line and `npx gulp scripts` with output suppressed; a failed build
  prints a warning and still exits 0. Fork-owned rule `application/libraries/Asset_cache_busting.php`
  (`token($root, $uri, $fallback)`: mtime of the served file, configured token when it is not a readable file);
  `asset_url()` keeps its upstream min-mapping and only calls the rule with the mapped URI and `FCPATH`. Hook
  documented in `docs/agents/validation-and-completion.md`.
- Rot vor Fix: `AssetCacheBustingTest` against a stub returning the configured token (old fixed-token behaviour) –
  `test_existing_file_gives_its_modification_time`, `test_minified_path_resolves_to_the_minified_file`,
  `test_non_minified_path_resolves_to_the_non_minified_file`: "Failed asserting that two strings are identical."
  (3 of 5 failed; missing-file and directory cases green as expected).
- Manual checks (agent, in the worktree, hook active via `git -c core.hooksPath=<copy of scripts/git-hooks>`,
  because the main checkout's hooks path has no post-checkout before the merge):
  - Temporary branch with a probe line appended to `assets/js/utils/date.js`. Switching to it printed
    `post-checkout: JS-Build (npx gulp scripts) für 1 veraltete Quelle(n)`; `date.min.js` contained the probe and
    its mtime moved 1792271669 → 1792271679. Switching back printed the line again and the probe was gone. Switching
    to a branch with identical JS printed nothing. Temporary branches deleted.
  - Hook with all `.min.js` fresh: no output, 0.13 s. File checkout (`$3 = 0`): no output.
  - Failing build (`npx` stub exiting 1, one stale source): rebuild line plus warning, exit 0.
  - `asset_url()` in the booted framework (throwaway PHPUnit probe, deleted): `assets/js/utils/date.js` →
    `…/date.min.js?1792271691` (= mtime of `date.min.js`), missing `assets/js/nope.js` → `…/nope.min.js?TSJ83`.
  - `bash scripts/test.sh` in the worktree: exit 3 (PHPUnit and all checks OK, smokes skipped).
- Open for the user (after the merge into `staging`): in the main checkout, switch `staging` ↔ a branch with a JS
  change, see the rebuild line, and confirm the browser runs the new code on a normal reload (no hard reload).
