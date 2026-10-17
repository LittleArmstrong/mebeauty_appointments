#!/usr/bin/env bash
#
# Shared helpers of the smoke tests (tests/smoke/*-smoke.sh). Source it right after `set -uo pipefail`:
#
#   . "$(dirname "$0")/lib.sh"
#
# Provides BASE (BASE_URL override), FAIL (exit status), TMP (temporary directory, removed by smoke_cleanup) and:
#   ok <text> / fail <text>       print a result line; fail sets FAIL=1
#   fixtures <command> [args]     run tests/smoke/fixtures.php in the php-fpm container
#   json <python-expression>      evaluate an expression on JSON from stdin (the parsed value is `d`)
#   backend_login                 log in as the seeded administrator; sets COOKIES and CSRF for backend requests
#   backend_post <path> [curl-args]  POST to a backend endpoint with session and CSRF token, prints the body
#   booking_refused               the body in $TMP/body is an expected booking refusal (spec 0023): marker set and
#                                 title "appointment_not_possible"
#
# This file is not a smoke test itself: scripts/test.sh and CI only run *-smoke.sh.

BASE="${BASE_URL:-http://localhost}"
FAIL=0
TMP=$(mktemp -d)
COOKIES="$TMP/cookies"
CSRF=""

fail() { echo "FAIL $1"; FAIL=1; }
ok() { echo "OK   $1"; }

fixtures() {
    docker compose exec -T php-fpm sh -c "APP_ENV=testing php tests/smoke/fixtures.php $*"
}

json() { python3 -c "import sys, json; d = json.load(sys.stdin); print($1)"; }

# Credentials of the administrator that `console install` seeds (Instance::seed()).
backend_login() {
    curl -s -o /dev/null -c "$COOKIES" -b "$COOKIES" "$BASE/index.php/login"
    CSRF=$(awk '$6 == "csrf_cookie" { print $7 }' "$COOKIES")
    curl -s -c "$COOKIES" -b "$COOKIES" --data-urlencode "csrf_token=$CSRF" \
        --data-urlencode "username=${SMOKE_ADMIN_USER:-administrator}" \
        --data-urlencode "password=${SMOKE_ADMIN_PASSWORD:-administrator}" \
        "$BASE/index.php/login/validate" | grep -q '"success":true'
}

backend_post() {
    local path=$1
    shift
    curl -s -c "$COOKIES" -b "$COOKIES" --data-urlencode "csrf_token=$CSRF" "$@" "$BASE/index.php/$path"
}

booking_refused() {
    local title
    title=$(fixtures lang appointment_not_possible | tail -1)
    [ "$(json "d.get('booking_refused') is True and d.get('title')" <"$TMP/body" 2>/dev/null)" = "$title" ]
}

smoke_cleanup() { rm -rf "$TMP"; }
