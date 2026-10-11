#!/usr/bin/env bash
#
# Full local validation in one command: starts the Docker stack, waits until MySQL and the site respond,
# then runs PHPUnit, all repository checks and all smoke tests. Keeps going after a failure and ends with a
# summary; exits 1 when at least one check failed, 2 when a prerequisite is missing.
#
# Usage (on the host, from anywhere):
#   bash scripts/test.sh
#
# The stack stays up afterwards; stop it with `docker compose down`. The smoke tests expect the settings
# theme=mebeauty and company_name=MeBeauty in the site's database (AGENTS.md).

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
wait_for "http://localhost:${NGINX_PORT:-80}/ antwortet nicht nach 120 s (docker compose logs nginx php-fpm)" 60 \
    curl -fsS -o /dev/null "http://localhost:${NGINX_PORT:-80}/"

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
run "check:assets" composer_in_container check:assets
run "check:scratch" composer_in_container check:scratch
run "check:views" composer_in_container check:views

for smoke in tests/smoke/*-smoke.sh; do
    run "smoke $(basename "$smoke" -smoke.sh)" bash "$smoke"
done

echo
echo "== Zusammenfassung"
printf '%s\n' "${results[@]}"

exit "$failed"
