#!/usr/bin/env bash
#
# Verifies that the email logo is embedded as a valid PNG.
#
# Triggers a real password email through Email_messages (lands in Mailpit via
# ssmtp), then inspects the message in Mailpit: it finds the inline part with
# ContentID "logo.png" and checks that the decoded bytes carry the PNG magic
# signature. This catches the double-base64 bug (addStringEmbeddedImage with an
# already-encoded string), which produces non-PNG bytes instead of an image.
#
# Requires the docker stack to be up (php-fpm + mailpit).

set -uo pipefail

RECIPIENT="${EMAIL_RECIPIENT:-smoke-test@example.com}"
MAILPIT="${MAILPIT_URL:-http://localhost:8025}"
FAIL=0

fail() { echo "FAIL $1"; FAIL=1; }
ok() { echo "OK   $1"; }

# 1. Trigger the email through the real Email_messages library.
docker compose exec -T -e "EMAIL_RECIPIENT=$RECIPIENT" php-fpm \
    sh -c 'APP_ENV=testing php tests/smoke/email-logo-trigger.php' >/dev/null 2>&1 \
    || { fail "trigger (email send)"; exit $FAIL; }

# 2. Find the newest message sent to the recipient.
ID=$(curl -fsS "$MAILPIT/api/v1/messages" | python3 -c '
import sys, json
d = json.load(sys.stdin)
target = sys.argv[1]
for m in d.get("messages", []):
    if any(a.get("Address") == target for a in m.get("To", [])):
        print(m["ID"])
        break
' "$RECIPIENT" 2>/dev/null)

if [ -z "$ID" ]; then
    fail "find message in Mailpit (recipient $RECIPIENT)"
    exit $FAIL
fi
ok "message in Mailpit ($ID)"

# 3. Find the inline logo part.
PART=$(curl -fsS "$MAILPIT/api/v1/message/$ID" | python3 -c '
import sys, json
d = json.load(sys.stdin)
for p in d.get("Inline", []):
    if p.get("ContentID") == "logo.png":
        print(p["PartID"])
        break
' 2>/dev/null)

if [ -z "$PART" ]; then
    fail "find inline logo part (ContentID logo.png)"
    exit $FAIL
fi
ok "inline logo part (ContentID logo.png, part $PART)"

# 4. Download the decoded part and verify it is a valid PNG.
TMP=$(mktemp)
curl -fsS "$MAILPIT/api/v1/message/$ID/part/$PART" -o "$TMP" 2>/dev/null \
    || { fail "download part"; rm -f "$TMP"; exit $FAIL; }

VALID=$(python3 -c 'import sys; print("yes" if sys.stdin.buffer.read(8) == b"\x89PNG\r\n\x1a\n" else "no")' < "$TMP")
rm -f "$TMP"

if [ "$VALID" = "yes" ]; then
    ok "embedded logo is a valid PNG"
else
    fail "embedded logo is NOT a valid PNG (double-base64 bug?)"
fi

exit $FAIL
