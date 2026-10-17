# Validation & Completion

## Validation

A pre-commit hook (`scripts/git-hooks/pre-commit`) blocks the commit when a staged
file fails one of these checks:

- JS syntax (`node --check`) and PHP syntax (`php -l` inside the `php-fpm` container)
- Prettier format of staged fork-owned files (`bash scripts/check-format.sh --staged`)
- spec tracker, when `.scratch/` files are staged (`php scripts/scratch-index.php check`)
- accidentally staged backup files (`.backup`, `.bak`, `.orig`)

A check whose tool is missing (`npx`, running container) is skipped with a warning.

A post-checkout hook (`scripts/git-hooks/post-checkout`) rebuilds the minified JavaScript
after a branch switch when a source is newer than its `.min.js` (`scripts/lib/js-build.sh`,
the same check `scripts/test.sh` uses). It prints one line when it rebuilds, nothing otherwise,
and only warns when the build fails. Asset URLs carry the served file's modification time
(`Asset_cache_busting`), so a normal reload picks up a rebuilt file.

The git config is local and not committed. Run this setup step once per clone (worktrees share it):

```bash
git config core.hooksPath scripts/git-hooks
git config merge.scratch-index.driver true
```

The first line activates both hooks. The second registers the merge driver that `.gitattributes` assigns to
`.scratch/INDEX.md`. A merge then keeps the current side of the index instead of producing conflict markers.
Regenerate the index right after the merge (`docs/agents/issue-tracker.md`).

After making changes:

1. Inspect the diff.
2. Run the full validation on the host:

   ```bash
   bash scripts/test.sh
   ```

   It starts the stack, waits until it responds, runs PHPUnit, every check and every smoke test,
   and ends with a summary (exit 1 on any failure, 2 when a prerequisite is missing; the script
   header lists the steps).
   Prerequisites you provide:

   - `config.php` in the repository root (`cp config-sample.php config.php`); PHPUnit boots the
     framework and needs it plus MySQL.
   - In a linked worktree under `.claude/worktrees/` it runs everything against the worktree
     through the main checkout's stack: PHPUnit and the checks in the worktree's directory, the
     smoke tests against `http://localhost/.claude/worktrees/<name>` (nginx serves the worktree
     there; its `config.php` copy gets that `BASE_URL`). The database is shared: smokes create and
     purge only their own `smoke-…` fixtures. After the merge the orchestrator runs it once more in
     the main checkout. A worktree outside the repository directory is not visible to the
     containers and aborts.

3. Check for accidental modifications.
4. Report exactly what was executed and the result.

Do not claim a test passed unless it was actually executed.

A spec with user-visible behaviour adds its manual test cases to `docs/manual-tests.md`
(one per observable acceptance criterion, with the ticket reference) and its entry to the
top release section of `CHANGELOG.md`.

If validation fails:

- Investigate failures caused by the current changes.
- Fix them when appropriate.
- Re-run validation.
- Clearly report unresolved failures.

## Completion

Before finishing a task, show:

```bash
git status
git diff --stat
git diff
```

Then report:

- Summary of changes
- Files changed
- Tests/checks executed
- Test/check results
- Remaining issues
- Suggested next step

If this is an authorized workflow commit point:

1. Review the final diff.
2. Stage only files belonging to the current workflow step.
3. Verify the staged diff.
4. Create the appropriate commit.
5. Show the commit hash and message.
6. Show the remaining working-tree status.

Do not push unless explicitly requested. After a requested push, check the CI result
with `gh run list --branch <branch>` (and `gh run view <id> --log-failed` on a failure)
and report it.

Do not merge unless explicitly requested.
