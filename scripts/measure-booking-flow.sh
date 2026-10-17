#!/usr/bin/env bash
#
# Booking-Flow measurement in a real browser (docs/visual-measurement.md, spec 0022/02).
#
# Usage (on the host, main checkout or worktree; the stack must be running):
#   npm run measure:booking [-- --viewport=390x844 --theme=mebeauty --timezone=UTC --locale=en-US --json]
#
# Creates its own test customers and appointments through tests/smoke/fixtures.php (never existing customers),
# runs tests/playwright/booking-flow.js in the pinned Playwright image with host networking against the site
# (BASE_URL, default http://localhost; nginx serves the main checkout) and removes the test data afterwards, also
# when the run fails. The walk never submits a booking, so it sends no mails.

set -euo pipefail

cd "$(dirname "$0")/.."
. scripts/lib/stack.sh

IMAGE=mcr.microsoft.com/playwright:v1.53.0-noble
VERSION=1.53.0

die() {
    echo "measure-booking-flow: $1" >&2
    exit 2
}

installed=$(node -p "require('./node_modules/playwright/package.json').version" 2>/dev/null || true)
[ "$installed" = "$VERSION" ] || die "playwright@$VERSION fehlt in node_modules (gefunden: ${installed:-nichts}) – npm ci"
[ -n "$(stack ps -q --status running php-fpm 2>/dev/null)" ] || die "Stack läuft nicht – docker compose up -d"

# The fixtures run in the main checkout, which nginx serves.
fixtures() {
    stack exec -T php-fpm sh -c 'APP_ENV=testing php tests/smoke/fixtures.php "$@"' fixtures "$@"
}

APPOINTMENTS=""
CUSTOMERS=""
cleanup() {
    if [ -n "$CUSTOMERS" ]; then
        fixtures cleanup "$APPOINTMENTS" "$CUSTOMERS" >/dev/null ||
            echo "measure-booking-flow: Testdaten nicht entfernt (Termine $APPOINTMENTS, Kunden $CUSTOMERS)" >&2
    fi
}
trap cleanup EXIT

FIXTURE=$(fixtures setup | tail -1)
field() { python3 -c "import sys, json; d = json.loads(sys.argv[1]); print($1)" "$FIXTURE"; }
APPOINTMENTS=$(field "f\"{d['victim']['id']},{d['owner']['id']}\"")
CUSTOMERS=$(field "f\"{d['victim']['customer_id']},{d['owner']['customer_id']}\"")
FIXTURE_CUSTOMER=$(fixtures customer "$(field "d['owner']['customer_id']")" | tail -1)
export FIXTURE FIXTURE_CUSTOMER

# Mount the main checkout under its host path, so a worktree's node_modules link resolves too.
mounts=(-v "$STACK_MAIN_DIR:$STACK_MAIN_DIR:ro")
case "$STACK_TOPLEVEL" in
    "$STACK_MAIN_DIR" | "$STACK_MAIN_DIR"/*) ;;
    *) mounts+=(-v "$STACK_TOPLEVEL:$STACK_TOPLEVEL:ro") ;;
esac

docker run --rm --init --network host "${mounts[@]}" -w "$STACK_TOPLEVEL" \
    --user "$(id -u):$(id -g)" -e HOME=/tmp \
    -e BASE_URL="${BASE_URL:-http://localhost}" -e FIXTURE -e FIXTURE_CUSTOMER \
    "$IMAGE" node tests/playwright/booking-flow.js "$@"
