# Staleness of the minified JavaScript, sourced by scripts/test.sh and the post-checkout hook.
#
# With DEBUG_MODE = false the site loads the gitignored assets/js/**/*.min.js, which only `npx gulp scripts`
# rebuilds. A source is stale when it is newer than its .min.js or has none. gulp gives the .min.js the source's
# mtime truncated to milliseconds, so whole seconds are compared. Run from the checkout root.
#
# Provides:
#   js_stale_scripts  print the stale sources, one per line (nothing when all are fresh)

js_stale_scripts() {
    find assets/js -name '*.js' ! -name '*.min.js' -print0 | while IFS= read -r -d '' src; do
        local min="${src%.js}.min.js"
        [ -f "$min" ] && [ "$(stat -c %Y "$src")" -le "$(stat -c %Y "$min")" ] || echo "$src"
    done
}
