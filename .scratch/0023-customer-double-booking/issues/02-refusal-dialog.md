# 02: Customer-facing refusal dialog and branded error title

**What to build:** Expected booking refusals (customer overlap, requested hour no longer available) are marked by
the server and shown on the booking page in the message dialog with the title "Termin nicht möglich" /
"Appointment not possible" and a plain text, without the "unexpected problem" heading; the wizard keeps all
entered data. The overlap text becomes "Sie haben zu dieser Zeit bereits einen Termin bei uns. Bitte wählen Sie
eine andere Uhrzeit." / "You already have an appointment with us at this time. Please choose another time.".
Unexpected errors keep the generic dialog, titled with the company name instead of "Easy!Appointments" (booking
page and backend).

**Blocked by:** 01

**Status:** ready-for-agent

- [ ] Server marks expected refusals; smoke asserts the marker for the overlap and the unavailable-hour case
- [ ] Booking page shows expected refusals with fitting title and plain text; entered data kept
- [ ] New German and English wording; other languages unchanged
- [ ] Generic error dialog titled with the company name (booking page and backend)
- [ ] Manual test cases in `docs/manual-tests.md` (refusal DE/EN, unexpected error title)
- [ ] `Rot vor Fix:` line recorded
- [ ] Visual check by the user (ends as `needs-verification`)
- [ ] `bash scripts/test.sh` green

## Comments
