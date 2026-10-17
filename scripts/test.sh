#!/usr/bin/env bash
#
# Full local validation in one command: starts the Docker stack, waits until MySQL and the site respond,
# rebuilds stale minified JS and the local spec index, then runs PHPUnit, all repository checks and all smoke tests. Keeps going after a
# failure and ends with a summary; exits 1 when at least one check failed, 2 when a prerequisite is missing.
#
# Usage (on the host, from anywhere):
#   bash scripts/test.sh
#
# In a linked worktree inside the main checkout (.claude/worktrees/, scripts/lib/stack.sh) everything runs against
# the worktree through the main checkout's stack: PHPUnit and the checks in php-fpm in the worktree's directory, the
# smoke tests against the worktree's URL below nginx (http://localhost/.claude/worktrees/<name>). The worktree gets
# copies of config.php (BASE_URL set to that URL, so CLI code builds links to the worktree) and vendor/ (autoload
# rebuilt for its own tests/) and a node_modules link. The database is shared with the main checkout and other
# worktrees; the smokes touch only their own smoke-… fixtures.
#
# The stack stays up afterwards; stop it with `docker compose down`. BASE_URL overrides the URL of the checkout
# under test, as in the smoke tests. Only `set -u`: a failing check is recorded and the run continues.

set -u

cd "$(dirname "$0")/.."
. scripts/lib/stack.sh
. scripts/lib/js-build.sh
. scripts/lib/scratch-index.sh

abort() {
    echo "test.sh: $1" >&2
    exit 2
}

wait_for() {
    local description=$1 attempts=$2
    shift 2
    for _ in $(seq 1 "$attempts"); do
        "$@" >/dev/null 2>&1 && return 0
        sleep 2
    done
    abort "$description"
}

[ -n "$STACK_WORKDIR" ] || abort "Worktree liegt außerhalb von $STACK_MAIN_DIR – die Container sehen ihn nicht"
[ -f "$STACK_MAIN_DIR/config.php" ] || abort "config.php fehlt – cp config-sample.php config.php (docs/docker.md)"

stack up -d || abort "docker compose up -d fehlgeschlagen"

wait_for "MySQL antwortet nicht nach 60 s (docker compose logs mysql)" 30 \
    stack exec -T mysql mysql -uuser -ppassword easyappointments -e "SELECT 1"
wait_for "vendor/autoload.php fehlt nach 120 s – Composer-Installation im php-fpm-Container prüfen" 60 \
    stack exec -T php-fpm test -f vendor/autoload.php

# URL of the checkout under test; the smokes (tests/smoke/lib.sh) default to the same value.
export BASE_URL="${BASE_URL:-http://localhost$STACK_URL_PATH}"

if [ "$STACK_IN_WORKTREE" = true ]; then
    stack_link_host_path || abort "Host-Pfad $STACK_MAIN_DIR im php-fpm-Container nicht verlinkbar"
    [ -f config.php ] || cp "$STACK_MAIN_DIR/config.php" config.php
    # Also corrects a copy made before the worktree URL was set (Config::BASE_URL is used on the CLI only).
    sed -i "s|^\( *const BASE_URL = \)'[^']*';|\1'$BASE_URL';|" config.php
    grep -q "const BASE_URL = '$BASE_URL';" config.php || abort "BASE_URL in config.php des Worktrees nicht setzbar"
    if [ ! -d vendor ]; then
        cp -a "$STACK_MAIN_DIR/vendor" vendor
        stack_exec php-fpm composer dump-autoload --quiet || abort "composer dump-autoload im Worktree fehlgeschlagen"
    fi
    [ -e node_modules ] || ln -s "$(realpath --relative-to=. "$STACK_MAIN_DIR/node_modules")" node_modules
fi

wait_for "$BASE_URL/ antwortet nicht nach 120 s (docker compose logs nginx php-fpm)" 60 \
    curl -fsS -o /dev/null "$BASE_URL/"

# The smokes do not run JS, but the stack they leave behind is what the user checks by eye: rebuild stale
# minified scripts (scripts/lib/js-build.sh) and abort when the build fails or leaves one stale.
if [ -n "$(js_stale_scripts)" ]; then
    [ -x node_modules/.bin/gulp ] || abort "veraltetes .min.js, aber node_modules fehlt – npm ci"
    echo "== JS-Build (npx gulp scripts): $(js_stale_scripts | wc -l) Quelle(n) neuer als ihr .min.js"
    npx gulp scripts || abort "npx gulp scripts fehlgeschlagen"
    [ -z "$(js_stale_scripts)" ] || abort "nach npx gulp scripts noch veraltet: $(js_stale_scripts | tr '\n' ' ')"
fi

# The spec index is a local generated file (docs/agents/issue-tracker.md): keep this checkout's copy current.
scratch_index_refresh

results=()
failed=0

run() {
    local name=$1
    shift
    echo
    echo "== $name"
    if "$@"; then
        results+=("OK    $name")
    else
        results+=("FAIL  $name")
        failed=1
    fi
}

composer_in_container() {
    stack_exec php-fpm composer "$@"
}

run "phpunit" composer_in_container test
run "check:format" bash scripts/check-format.sh
run "check:migrations" composer_in_container check:migrations
run "migration rerun" bash scripts/migration-test-db.sh rerun easyappointments
run "migration drift" bash scripts/migration-test-db.sh drift easyappointments
run "check:assets" composer_in_container check:assets
run "check:scratch" composer_in_container check:scratch
run "check:views" composer_in_container check:views

# Smoke customers left over by an aborted earlier run (tests/smoke/fixtures.php purge).
stack_exec php-fpm sh -c "APP_ENV=testing php tests/smoke/fixtures.php purge" >/dev/null

for smoke in tests/smoke/*-smoke.sh; do
    run "smoke $(basename "$smoke" -smoke.sh)" bash "$smoke"
done

echo
echo "== Zusammenfassung"
printf '%s\n' "${results[@]}"

exit "$failed"
