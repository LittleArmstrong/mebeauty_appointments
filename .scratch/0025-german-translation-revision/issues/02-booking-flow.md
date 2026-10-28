# 02: Booking flow wording

**What to build:** All German texts of the booking wizard (service/person, time, details, confirmation, booking
messages and refusal dialogs) and the theme's `site_*` texts rewritten per the style guide. The person step asks
"Bei wem möchten Sie buchen?" with "Egal bei wem"; the WhatsApp button uses "Sie". Own keys where a shared key
cannot be worded right; English fixed only where functionally wrong.

**Blocked by:** 01

**Status:** ready-for-agent

- [ ] Wizard steps and booking messages reworded (German)
- [ ] Person step with question wording, "Egal bei wem"
- [ ] `site_*` texts in "Sie"
- [ ] Own keys where needed, in German and English
- [ ] Manual test cases in `docs/manual-tests.md`
- [ ] `Rot vor Fix:` line recorded
- [ ] `bash scripts/test.sh` green
- [ ] Visual check by the user (ends as `needs-verification`)
