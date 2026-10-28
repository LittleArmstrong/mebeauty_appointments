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
(`docker/nginx/nginx.conf:13` serves worktrees below `/var/www/html`). The purge deletes every `smoke-…` record and
`language-selector-smoke.sh` flips global settings, so `scripts/test.sh` serializes the smoke phase of all checkouts
with `.git/smoke.lock`. Parallel implementers wait for each other's smokes.

**Proposal:** If the waiting starts to slow runs down, either

- give each worktree its own Docker Compose project (`docker compose -p <worktree>`) with its own port and database,
  or
- name fixtures per run (`smoke-<run>-…`), let the purge delete only leftovers older than e.g. 30 minutes, and keep
  the lock only for the smokes that change global settings.

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

## Merge script for the orchestrator, with recorded green runs

Added: 2026-10-18, from the retro of the 0024 run.

**Problem:** In `/implement-spec` every ticket merge into the integration branch ran as its own merger subagent
(30–46k tokens each) for the same fixed steps: clean-tree check, fast-forward or `--no-ff` with trailer,
`bash scripts/test.sh`, ticking the ticket. Two merges must not run at once in the main checkout, so the orchestrator
serialized them by hand. Whether a ticket's "`bash scripts/test.sh` green" box is true is only stated in prose
(0024/01 and 0024/05 kept a stale "exit 3" until the spec review).

**Proposal:** `scripts/merge-ticket.sh <branch> <integration-branch>` does the fixed steps in one Bash call under a
lock and prints the exit code; `docs/agents/git-workflow.md` points to it. Optionally `scripts/test.sh` records the
commit SHA of its last green run in `.git/`, and `check:scratch` refuses `resolved`/`needs-verification` for a spec
whose integration commit has no recorded green run.

**Open questions:**

- Does a script replace the merger subagent in the `/implement-spec` skill text, or does the orchestrator call it
  itself?
- Is a SHA file in `.git/` enough, or does CI need the same evidence?

## Manual checks that need a fresh session

Added: 2026-10-18, from the retro of the 0024 run.

**Problem:** 0024/01 asked the implementer to check that a new agent worktree starts from the local `HEAD`. That needs
a new Claude Code session, which an implementer cannot start; the box stayed open until later tickets' worktrees
provided the evidence.

**Proposal:** `docs/agents/issue-tracker.md` (checklist rules): a manual check that needs a new session, a restart or
an action outside the implementer's worktree is written as a check "by the user" or assigned to the orchestrator in
the ticket text.

**Open questions:**

- Is one sentence in the tracker doc enough, or should the spec template carry an "who verifies" column?

## Site-Chrome in the booking flow: keep, slim down or drop

Added: 2026-10-28, from the translation/frontend grilling session (decision postponed by the user).

**Problem:** The booking flow rebuilds the mebeauty-Site's navbar, intro, footer and WhatsApp button as theme
partials (`application/views/themes/mebeauty/site_navbar.php`, `site_intro.php`, `site_footer.php`,
`site_whatsapp.php`; ADR 0006). Navigation, contact data and opening hours therefore exist twice and every change on
the Astro site has to be repeated by hand; their texts sit in the core language file
(`application/language/german/translations_lang.php:650-665`) instead of with the theme. The repository is public,
so the customer-specific chrome is the most visible MeBeauty-specific part of it.

**Proposal:** Options discussed:

- A) Slim header (logo, "back to the website") and a minimal footer with contact/imprint; site navigation and the
  full footer are dropped. Removes the double maintenance, booking no longer looks 1:1 like the site.
- B) Keep as is (ADR 0006), optionally move the `site_*` keys into a theme-owned language file.
- C) Take header/footer HTML from the Astro build at deploy time. Practically blocked by Tailwind vs. Bootstrap
  (same reason as ADR 0001).

**Open questions:**

- How important is the 1:1 site look in the booking flow compared with less maintenance?
- If A: which elements are legally or practically required in the slim footer (imprint, privacy, phone)?
- Does A need a follow-up ADR superseding the Site-Chrome part of ADR 0006?
