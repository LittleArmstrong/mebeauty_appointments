#!/usr/bin/env bash
#
# HTTP smoke test of the setting display_language_selector (spec 0014): with 0 the booking flow has no language
# selector, with 1 it has one. The footer itself is forced on (display_booking_footer, spec 0019). The original values
# are restored on exit.
#
# Requires the docker stack to be up (php-fpm, nginx, mysql).

set -uo pipefail

. "$(dirname "$0")/lib.sh"

has_selector() { local body; body=$(curl -fsS "$BASE/index.php/booking") && grep -q 'id="select-language"' <<<"$body"; }

setting() {
    fixtures setting "$@" | tail -1
}

ORIGINAL=$(setting display_language_selector)
ORIGINAL_FOOTER=$(setting display_booking_footer)
trap 'setting display_language_selector "'"'"'$ORIGINAL'"'"'" >/dev/null; setting display_booking_footer "'"'"'$ORIGINAL_FOOTER'"'"'" >/dev/null; smoke_cleanup' EXIT

setting display_booking_footer "'1'" >/dev/null

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
