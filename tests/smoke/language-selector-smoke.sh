#!/usr/bin/env bash
#
# HTTP smoke test of the setting display_language_selector (spec 0014): with 0 the booking flow has no language
# selector, with 1 it has one. The original value is restored on exit.
#
# Requires the docker stack to be up (php-fpm, nginx, mysql).

set -uo pipefail

BASE="${BASE_URL:-http://localhost}"
FAIL=0

fail() { echo "FAIL $1"; FAIL=1; }
has_selector() { local body; body=$(curl -fsS "$BASE/index.php/booking") && grep -q 'id="select-language"' <<<"$body"; }
ok() { echo "OK   $1"; }

setting() {
    docker compose exec -T php-fpm sh -c "APP_ENV=testing php tests/smoke/fixtures.php setting $*" | tail -1
}

ORIGINAL=$(setting display_language_selector)
trap 'setting display_language_selector "'"'"'$ORIGINAL'"'"'" >/dev/null' EXIT

setting display_language_selector "'0'" >/dev/null
if has_selector; then
    fail "language selector hidden with display_language_selector=0"
else
    ok "language selector hidden with display_language_selector=0"
fi

setting display_language_selector "'1'" >/dev/null
if has_selector; then
    ok "language selector shown with display_language_selector=1"
else
    fail "language selector shown with display_language_selector=1"
fi

exit $FAIL
