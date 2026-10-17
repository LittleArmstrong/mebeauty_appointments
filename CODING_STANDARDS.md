# Coding Standards

Conventions enforced during code review. Tooling already catches syntax and the
email-logo smoke test catches the base64 gotcha; these are the judgement calls a
guardrail cannot enforce.

## Upstream files only call fork code

The logic of a fork feature lives in fork-owned libraries and models (pattern: `Booking_policy`,
`Appointment_contact_rules` as pure rule modules with unit tests). Upstream controllers, libraries, views and
page scripts (`assets/js/pages/*.js`) only gain calls into them and display adjustments; a decision (if-cascade,
merge, branch choice) written inline in an upstream file belongs in the rule module (ADR 0005, keeps the upstream
diff and sync conflicts small). Fork JS logic lives in a fork-owned utility module under `assets/js/utils/`
(pattern: `wizard_scroll.js`) that the page script calls.

## Brand values come from settings

Original Easy!Appointments surfaces (backend header, emails, booking wizard
card, page titles, login/logout/recovery/about) resolve brand values through
settings and never hardcode a literal brand:

- Name → `setting('company_name', DEFAULT_COMPANY_NAME)`
- Logo → `setting('company_logo') ?: asset_url('assets/img/logo.png')`

No literal `"MeBeauty"`/`"Easy!Appointments"` strings or literal logo paths
belong in these views.

Exception: the site chrome of a theme (`application/views/themes/<theme>/site_navbar.php`, `site_intro`,
`site_footer`, `site_whatsapp`; ADR 0006) is a per-customer marketing asset and is intentionally not
settings-driven.

## Image/logo/favicon changes

Verify the target asset's role in `docs/assets.md` before replacing or re-wiring
it. In particular: `assets/img/logo.png` is a fallback, not the favicon; the
favicon is `assets/img/favicon.ico` only.

## View output goes through `e()`

Fork-owned views print variables, `setting()`, `vars()` and `html_vars()` values via `e()`;
`composer check:views` enforces this. Upstream layouts print some view variables raw
(`page_title` in `application/views/layouts/*_layout.php`), and the check does not cover
upstream views: a value passed into such a variable is escaped where it is set, e.g.
`'page_title' => lang('page_title') . ' ' . e($company_name)` (`Booking::index()`).

## Tests go red

A new test in a ticket that changes behaviour (feat/fix) is red against the old
behaviour before it turns green with the change. Its test data produces exactly the
edge case the test name claims (a test about breaks has a break in its working plan).
The ticket comment records the evidence: `Rot vor Fix: <test(s)> – <failure message>`,
from running the test against the old code or a simulated old behaviour. Refactors and
docs-only tickets keep their existing tests green instead and record `Rot vor Fix: entfällt – <reason>`.
`check:scratch` enforces that the line exists (tickets from spec 0017 on, status `needs-verification`/`resolved`;
from spec 0022 on also as soon as every implementation item of the checklist is ticked, see
`docs/agents/issue-tracker.md`); the review judges whether the evidence is real.

## Global scripts use only what every page loads

Global scripts (`assets/js/app.js`, `assets/js/utils/message.js`) run on every page, including the installation
page, which loads only jQuery, Popper, Bootstrap and a few utilities (`application/views/pages/installation.php`).
They use only what that page also loads (no `App.Utils.String` there); dynamic text they insert arrives escaped from
the server instead of being escaped in the script.

## Test data cleanup works from the raw setup output

A script that creates test data stores the raw setup output first, before parsing or anything else that can fail,
and its cleanup (an exit trap or `finally`) works from that stored output. A failing parse or a setup that prints its
data and then exits non-zero still removes the test data (pattern: `scripts/measure-booking-flow.sh`).

## Tooling survives parallel runs

Agents run in parallel worktrees on one stack and one database. A test or tooling script that changes shared state
(database rows, global settings, files in the main checkout) is safe when two runs overlap: it serializes the shared
part with a lock (pattern: the smoke phase of `scripts/test.sh` under `.git/smoke.lock`) or keeps to data named for
its own run. A cleanup that deletes by a shared prefix counts as shared state.
