# Validation & Completion

## Validation

A pre-commit hook (`scripts/git-hooks/pre-commit`) blocks the commit when a staged
file fails one of these checks:

- JS syntax (`node --check`) and PHP syntax (`php -l` inside the `php-fpm` container)
- Prettier format of staged fork-owned files (`bash scripts/check-format.sh --staged`)
- spec tracker, when `.scratch/` files are staged (`php scripts/scratch-index.php check`)
- accidentally staged backup files (`.backup`, `.bak`, `.orig`)

A check whose tool is missing (`npx`, running container) is skipped with a warning.
The hook is activated via `git config core.hooksPath scripts/git-hooks` and must be
re-enabled on a fresh clone (the config is local, not committed).

After making changes:

1. Inspect the diff.
2. Run the PHPUnit suite and syntax checks. All PHP/Composer commands must run
   inside the `php-fpm` container (the host `php` binary is a ddev shim and will
   fail). See `docs/docker.md` for the full command list.

   ```bash
   docker compose exec -T php-fpm composer test          # PHPUnit suite
   docker compose exec -T php-fpm php -l <file>          # PHP syntax, per changed file
   node --check <file>                                    # JS syntax, per changed file
   bash tests/smoke/mebeauty-layout-smoke.sh             # public-page HTTP smoke (site must be up)
   ```

3. Check for accidental modifications.
4. Report exactly what was executed and the result.

Do not claim a test passed unless it was actually executed.

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

Do not push unless explicitly requested.

Do not merge unless explicitly requested.
