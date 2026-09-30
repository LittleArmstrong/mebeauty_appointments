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

check_missing() {
    local name="$1" url="$2" pattern="$3"
    local body
    body=$(curl -fsS "$url" 2>/dev/null) || { echo "FAIL $name (HTTP-Fehler)"; FAIL=1; return; }
    body=$(sed '/<script/,/<\/script>/d' <<< "$body")
    if grep -q "$pattern" <<< "$body"; then echo "FAIL $name (Muster darf nicht vorkommen: $pattern)"; FAIL=1; else echo "OK   $name"; fi
}

check "booking-theme"      "$BASE/index.php/booking" 'themes/mebeauty'
check "booking-black-page" "$BASE/index.php/booking" 'site-page'
check "booking-navbar-logo" "$BASE/index.php/booking" 'mebeauty/logo\.png'
check "booking-nav-links"   "$BASE/index.php/booking" 'mebeauty-koeln\.de/Behandlungen'
check "booking-mobile-menu" "$BASE/index.php/booking" 'navbar-mobile-menu'
check "booking-hero"       "$BASE/index.php/booking" 'site-intro'
check "booking-hero-title" "$BASE/index.php/booking" '>Buchung</h1>'
check "booking-footer-treatments" "$BASE/index.php/booking" 'mebeauty-koeln\.de/Behandlungen#01_Gesichtsbehandlungen'
check "booking-footer-contact"     "$BASE/index.php/booking" 'Fußfallstr\. 25a'
check "booking-footer-legal"       "$BASE/index.php/booking" 'mebeauty-koeln\.de/Impressum">Impressum</a></li>'
check "booking-whatsapp"           "$BASE/index.php/booking" 'wa\.me/4917619256689'
check "booking-steps"      "$BASE/index.php/booking" 'id="step-1"'
check "booking-title"      "$BASE/index.php/booking" 'Buchung | MeBeauty'
check "booking-lang-badge" "$BASE/index.php/booking" 'id="select-language"'
check_missing "booking-no-ea-brand" "$BASE/index.php/booking" 'Easy!Appointments'
check_missing "booking-no-header"   "$BASE/index.php/booking" 'id="company-name"'

exit $FAIL
