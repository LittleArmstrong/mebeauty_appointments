#!/usr/bin/env bash
set -uo pipefail

BASE="${BASE_URL:-http://localhost}"
FAIL=0

check() {
    local name="$1" url="$2" pattern="$3"
    local body
    body=$(curl -fsS "$url" 2>/dev/null) || { echo "FAIL $name (HTTP-Fehler)"; FAIL=1; return; }
    if grep -q "$pattern" <<< "$body"; then echo "OK   $name"; else echo "FAIL $name (Muster fehlt: $pattern)"; FAIL=1; fi
}

check "booking-theme"      "$BASE/index.php/booking" 'themes/mebeauty'
check "booking-black-page" "$BASE/index.php/booking" 'site-page'

exit $FAIL
