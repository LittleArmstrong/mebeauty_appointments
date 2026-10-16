# 01: Refuse every overlap with the customer's own appointments

**What to build:** A booking or Manage-Link reschedule is refused when the customer already has an appointment
that overlaps the requested time by any amount (existing start < requested end and existing end > requested
start). Back-to-back stays possible, the rescheduled appointment itself and unavailabilities do not count. The
booking form and the Vormerkung confirmation use one fork-owned check instead of two containment queries; an
overlap at confirmation still leads to the neutral page.

**Blocked by:** None (can start immediately)

**Status:** ready-for-agent

- [ ] One fork-owned overlap check, called from the booking form and the Vormerkung confirmation
- [ ] Strict comparisons: touching edges are no conflict; own rescheduled appointment and unavailabilities excluded
- [ ] HTTP smoke cases: partial overlap start/end, containment both ways, back-to-back before/after, other
      provider, Manage-Link shift of own appointment, Manage-Link onto another own appointment
- [ ] Email-verification smoke: partial overlap at confirmation → neutral page, nothing booked
- [ ] `Rot vor Fix:` partial-overlap cases fail against the old containment query (evidence here)
- [ ] `bash scripts/test.sh` green

## Comments
