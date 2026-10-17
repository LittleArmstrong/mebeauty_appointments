# Freshness of the npm packages, sourced by scripts/test.sh and the post-checkout and post-merge hooks.
#
# npm writes node_modules/.package-lock.json on every install. When package-lock.json is newer (a checkout or merge
# changed it) or node_modules has no such file, the installed packages may not match the lockfile and `npm ci` is
# due. The hooks only report that; scripts/test.sh installs. Only the main checkout counts: a linked worktree uses a
# link to the main checkout's node_modules (scripts/test.sh), and its own lockfile's mtime is its creation time.
# Needs scripts/lib/stack.sh. Run from the checkout root.
#
# Provides:
#   npm_lock_stale   succeed in the main checkout when node_modules is older than package-lock.json or not installed
#   npm_lock_notice  print one line naming the hook when npm_lock_stale holds, nothing otherwise ($1: hook name)
#   npm_worktree_lock_notice  in a worktree: print one line when its package-lock.json differs in content from the
#                    main checkout's, so the linked node_modules does not match it (nothing is installed)

npm_lock_stale() {
    [ "$STACK_IN_WORKTREE" = false ] && [ -f package-lock.json ] || return 1
    [ ! -f node_modules/.package-lock.json ] || [ package-lock.json -nt node_modules/.package-lock.json ]
}

npm_lock_notice() {
    npm_lock_stale || return 0
    echo "$1: package-lock.json ist neuer als node_modules – npm ci fällig (scripts/test.sh installiert selbst)"
}

npm_worktree_lock_notice() {
    [ "$STACK_IN_WORKTREE" = true ] && [ -f package-lock.json ] || return 0
    cmp -s package-lock.json "$STACK_MAIN_DIR/package-lock.json" && return 0
    echo "$1: package-lock.json weicht vom Hauptcheckout ab – das verlinkte node_modules passt nicht dazu (kein npm ci)"
}
