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

VERSION=1.53.0 # pin: package.json devDependencies "playwright"
IMAGE=mcr.microsoft.com/playwright:v$VERSION-noble

die() {
    echo "measure-booking-flow: $1" >&2
    exit 2
}

installed=$(node -p "require('./node_modules/playwright/package.json').version" 2>/dev/null || true)
[ "$installed" = "$VERSION" ] || die "playwright@$VERSION fehlt in node_modules (gefunden: ${installed:-nichts}) – npm ci"
# Inline instead of stack_php_running: the fixtures run in the main checkout, so a worktree outside the mount
# (empty STACK_WORKDIR) is fine here.
[ -n "$(stack ps -q --status running php-fpm 2>/dev/null)" ] || die "Stack läuft nicht – docker compose up -d"

# The fixtures run in the main checkout, which nginx serves.
fixtures() {
    stack exec -T php-fpm sh -c 'APP_ENV=testing php tests/smoke/fixtures.php "$@"' fixtures "$@"
}

# Value at a dotted path (e.g. owner.customer_id) of a JSON document; no code is interpolated.
field() {
    node -p 'process.argv[2].split(".").reduce((o, k) => o[k], JSON.parse(process.argv[1]))' "$1" "$2"
}

# Cleanup works from the raw setup output, stored before anything can fail: a setup that prints its JSON and then
# exits non-zero, or a failing parse, still removes the test data.
FIXTURE_RAW=""
cleanup() {
    local fixture appointments customers
    fixture=$(printf '%s\n' "$FIXTURE_RAW" | grep '^{' | tail -1 || true)
    [ -n "$fixture" ] || return 0
    if appointments="$(field "$fixture" victim.id),$(field "$fixture" owner.id)" &&
        customers="$(field "$fixture" victim.customer_id),$(field "$fixture" owner.customer_id)" &&
        fixtures cleanup "$appointments" "$customers" >/dev/null; then
        return 0
    fi
    echo "measure-booking-flow: Testdaten nicht entfernt – Setup-Ausgabe: $fixture" >&2
}
trap cleanup EXIT

FIXTURE_RAW=$(fixtures setup) || die "fixtures setup fehlgeschlagen"
FIXTURE=$(printf '%s\n' "$FIXTURE_RAW" | tail -1)
FIXTURE_CUSTOMER=$(fixtures customer "$(field "$FIXTURE" owner.customer_id)" | tail -1)
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
