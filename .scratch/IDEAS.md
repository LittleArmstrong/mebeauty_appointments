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

## Own stack per worktree

Added: 2026-10-17, from the retro of the 0020/0021 run; updated 2026-10-18 (spec 0024).

**Problem:** Since 0024 the smokes of a worktree run against the shared stack and database
(`docker/nginx/nginx.conf:13` serves worktrees below `/var/www/html`). Parallel smoke runs share data; so far
harmless, because every smoke uses its own `smoke-…` fixtures.

**Proposal:** If parallel runs start to interfere, give each worktree its own Docker Compose project
(`docker compose -p <worktree>`) with its own port and database.

**Open questions:**

- Is the extra MySQL/Mailpit per worktree acceptable on the development machine?

## CI as the gate for agent branches

Added: 2026-10-18, from the retro of the 0023 run.

**Problem:** CI runs the full smoke suite in a clean stack on every push (`.github/workflows/ci.yml:3`,
`.github/workflows/ci.yml:76-151`); only a push to `staging` deploys (`.github/workflows/deploy.yml:11`). Agents never
push (`AGENTS.md`), so CI only sees branches the user pushes.

**Proposal:** Allow agents to push `agent/*` branches (never `staging`/`main`), so CI checks each branch before the
staging merge.

**Open questions:**

- Do the monthly GitHub Actions minutes cover one CI run per agent branch?
