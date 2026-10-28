# 04: Manage-Link page wording

**What to build:** The Manage-Link page speaks of "stornieren" and "umbuchen": the banner hint matches its button,
the button gets its own key ("Termin stornieren" / "Cancel appointment") instead of `delete`, the cancellation
reason prompt addresses the customer, and the deadline/locked messages and the "delete personal data" bar read
naturally. English hint and button fixed to match each other.

**Blocked by:** 01

**Status:** ready-for-agent

- [ ] Cancellation banner: hint and own button key match (German and English)
- [ ] Cancellation reason prompt reworded
- [ ] Deadline and locked messages reworded
- [ ] Delete-personal-data bar reworded
- [ ] Manual test cases in `docs/manual-tests.md`
- [ ] `Rot vor Fix:` line recorded
- [ ] `bash scripts/test.sh` green
- [ ] Visual check by the user (ends as `needs-verification`)
