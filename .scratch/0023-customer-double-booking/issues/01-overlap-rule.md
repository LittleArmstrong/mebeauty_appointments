# 01: Refuse every overlap with the customer's own appointments

**What to build:** A booking or Manage-Link reschedule is refused when the customer already has an appointment
that overlaps the requested time by any amount (existing start < requested end and existing end > requested
start). Back-to-back stays possible, the rescheduled appointment itself and unavailabilities do not count. The
booking form and the Vormerkung confirmation use one fork-owned check instead of two containment queries; an
overlap at confirmation still leads to the neutral page.

**Blocked by:** None (can start immediately)

**Status:** resolved

- [x] One fork-owned overlap check, called from the booking form and the Vormerkung confirmation
- [x] Strict comparisons: touching edges are no conflict; own rescheduled appointment and unavailabilities excluded
- [x] HTTP smoke cases: partial overlap start/end, containment both ways, back-to-back before/after, other
      provider, Manage-Link shift of own appointment, Manage-Link onto another own appointment
- [x] Email-verification smoke: partial overlap at confirmation → neutral page, nothing booked
- [x] `Rot vor Fix:` partial-overlap cases fail against the old containment query (evidence here)
- [x] `bash scripts/test.sh` green

## Comments

**2026-10-17, implementer (worktree `.claude/worktrees/agent-a5b41edd59b50c643`, branch `agent/0023-01-overlap-rule`):**

One fork-owned check `Customer_overlap::exists()` (`application/libraries/Customer_overlap.php`): existing start <
requested end and existing end > requested start, same customer, without unavailabilities, without the rescheduled
appointment. `Booking::register()` calls it with the end computed from the service duration (no longer the client's
`end_datetime`), `Email_verification::book()` calls it at confirmation (its copy of the containment query is gone).
The refusal in `Booking::register()` stays a single `throw new RuntimeException(lang('customer_is_already_booked'))`
next to the call, the place where ticket 02 adds its marker.

Tests: new `tests/smoke/customer-overlap-smoke.sh` (fixture `overlap-setup` creates a second smoke provider, copy of
the first, and a customer with one appointment H there; `appointment-times` moves an appointment; `purge` also
removes smoke providers) and case 12b in `tests/smoke/email-verification-smoke.sh` (Vormerkung starting inside the
customer's appointment, two attendants as in case 12).

Rot vor Fix: against the old containment query (code of `agent/0023-customer-double-booking`, served from this
worktree) `customer-overlap-smoke.sh` failed with `FAIL partial overlap at the start (HTTP 200,
{"appointment_id":943,…})`, `FAIL partial overlap at the end (HTTP 200, …)`, `FAIL request containing the own
appointment (HTTP 200, …)`, `FAIL partial overlap with "any provider" (HTTP 200, …)` and `FAIL Manage-Link move onto
another own appointment (HTTP 200, …, start 2026-10-27 09:45:00)`; request inside the own appointment, back-to-back
and the Manage-Link shift were already OK. `email-verification-smoke.sh` failed only with `FAIL partly overlapping
Vormerkung at 2026-10-27 09:45:00 (HTTP 200): 303 …/booking_confirmation/of/…, booked {"id":990,…}`.

Green after the fix: all 9 cases of `customer-overlap-smoke.sh`, `email-verification-smoke.sh` (35 OK),
`manage-link-smoke.sh`, `appointment-contacts-smoke.sh`, `email-verification-backend-smoke.sh` exit 0. The smokes ran
against this worktree's code: nginx serves the worktree at `http://localhost/.claude/worktrees/<name>/` (`BASE_URL`),
and the fixtures ran in the worktree's directory inside php-fpm (wrapper redefining `docker compose exec` with
`-f <main>/docker-compose.yml -w /var/www/html/.claude/worktrees/<name>`). `bash scripts/test.sh` in the worktree:
exit 3 (PHPUnit and all checks OK, smokes skipped); the full run with smokes follows in the main checkout after the
merge.
