# Local spec index, sourced by scripts/test.sh and the post-checkout and post-merge hooks.
#
# .scratch/INDEX.md is generated and ignored by git (docs/agents/issue-tracker.md), so every checkout keeps its own
# copy current. PHP runs in php-fpm (the host `php` is a ddev shim); without a running stack nothing happens.
# Needs scripts/lib/stack.sh. Run from the checkout root.
#
# Provides:
#   scratch_index_refresh  regenerate .scratch/INDEX.md quietly; never fails

scratch_index_refresh() {
    [ -d .scratch ] && stack_php_running || return 0
    stack_exec php-fpm php scripts/scratch-index.php generate >/dev/null 2>&1 || true
}
