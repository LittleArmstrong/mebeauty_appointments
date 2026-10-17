# Ideas

Open points and ideas that are not decided yet. They are not specs: no number, no tickets, not in `INDEX.md`, and
`/implement-spec` ignores them. When an idea is decided, it becomes a spec (`/to-spec`) and its entry here is
removed. See `docs/agents/issue-tracker.md`.

## Notify the previous customer when an appointment is reassigned

Added: 2026-10-16, from manual test T-B-02.

**Problem:** When staff assign an existing appointment to a different customer in the backend appointment dialog,
the save handler loads only the appointment's new customer (`application/controllers/Calendar.php:410`) and sends
the "saved" notifications to that customer, provider, admins and secretaries
(`application/controllers/Calendar.php:427-436`). The previous customer is not notified; they still hold the booking
confirmation and assume the appointment is theirs. The new customer gets "Appointment details have changed" instead
of a booking confirmation.

**Proposal:** The previous customer receives a cancellation mail (as on deletion); the new customer receives a
booking confirmation instead of "details changed".

**Open questions:**

- Is reassigning used to correct a wrongly selected customer (who may never have read the first mail), or to hand
  an appointment over to someone else? This decides whether the cancellation mail helps or confuses.
- Does the same apply to customer changes made through API v1?

## Own browser URL per worktree

Added: 2026-10-17, from the retro of the 0020/0021 run.

**Problem:** nginx serves only the main checkout (`docker/nginx/nginx.conf:4-10`). Implementers in a worktree
cannot run the smoke tests (`scripts/test.sh` exits 3) or Playwright, and seeing a branch in the browser means
checking it out in the main checkout. Since 2026-10-17 validated branches are merged into `staging` and verified
there (`docs/agents/git-workflow.md`), which removes the branch switching for the user's visual check.

**Proposal:** If several specs regularly run in parallel, give each worktree its own Docker Compose project
(`docker compose -p <worktree>`) with its own port and database, so that parallel smokes do not share data.

**Open questions:**

- How often do specs actually run in parallel?
- Is the extra MySQL/Mailpit per worktree acceptable on the development machine?
