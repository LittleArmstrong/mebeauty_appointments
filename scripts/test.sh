#!/usr/bin/env bash
#
# Full local validation in one command: starts the Docker stack, waits until MySQL and the site respond,
# rebuilds stale minified JS, then runs PHPUnit, all repository checks and all smoke tests. Keeps going after a failure and ends with a
# summary; exits 1 when at least one check failed, 2 when a prerequisite is missing.
#
# Usage (on the host, from anywhere):
#   bash scripts/test.sh
#
# The stack stays up afterwards; stop it with `docker compose down`. BASE_URL is the same override the smoke
# tests use. Only `set -u`: a failing check is recorded and the run continues.

set -u

cd "$(dirname "$0")/.."

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

[ -f config.php ] || abort "config.php fehlt – cp config-sample.php config.php (docs/docker.md)"

docker compose up -d || abort "docker compose up -d fehlgeschlagen"

wait_for "MySQL antwortet nicht nach 60 s (docker compose logs mysql)" 30 \
    docker compose exec -T mysql mysql -uuser -ppassword easyappointments -e "SELECT 1"
wait_for "vendor/autoload.php fehlt nach 120 s – Composer-Installation im php-fpm-Container prüfen" 60 \
    docker compose exec -T php-fpm test -f vendor/autoload.php
wait_for "${BASE_URL:-http://localhost}/ antwortet nicht nach 120 s (docker compose logs nginx php-fpm)" 60 \
    curl -fsS -o /dev/null "${BASE_URL:-http://localhost}/"

# With DEBUG_MODE = false the site loads the gitignored assets/js/**/*.min.js. The smokes do not run JS, but
# the stack they leave behind is what the user checks by eye: rebuild when a source is newer than its .min.js
# and abort when the build fails or leaves one stale. gulp gives the .min.js the source's mtime truncated to
# milliseconds, so whole seconds are compared.
stale_scripts() {
    find assets/js -name '*.js' ! -name '*.min.js' -print0 | while IFS= read -r -d '' src; do
        local min="${src%.js}.min.js"
        [ -f "$min" ] && [ "$(stat -c %Y "$src")" -le "$(stat -c %Y "$min")" ] || echo "$src"
    done
}
if [ -n "$(stale_scripts)" ]; then
    [ -x node_modules/.bin/gulp ] || abort "veraltetes .min.js, aber node_modules fehlt – npm ci"
    echo "== JS-Build (npx gulp scripts): $(stale_scripts | wc -l) Quelle(n) neuer als ihr .min.js"
    npx gulp scripts || abort "npx gulp scripts fehlgeschlagen"
    [ -z "$(stale_scripts)" ] || abort "nach npx gulp scripts noch veraltet: $(stale_scripts | tr '\n' ' ')"
fi

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
    docker compose exec -T php-fpm composer "$@"
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
docker compose exec -T php-fpm sh -c "APP_ENV=testing php tests/smoke/fixtures.php purge" >/dev/null

for smoke in tests/smoke/*-smoke.sh; do
    run "smoke $(basename "$smoke" -smoke.sh)" bash "$smoke"
done

echo
echo "== Zusammenfassung"
printf '%s\n' "${results[@]}"

exit "$failed"
