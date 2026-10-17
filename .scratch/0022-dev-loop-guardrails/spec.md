# Dev-loop guardrails: fresh JS, measurable Booking-Flow, conflict-free parallel specs

Status: needs-verification
Created: 2026-10-16
Closed: –
Priority: P2
Depends-on: –

## Problem Statement

The retro of specs 0020 and 0021 (2026-10-16) found several gaps in the local development loop. They cost time,
and in one case they made the user's visual check report a false bug.

- **Stale JavaScript after a branch switch.** With `DEBUG_MODE = false` (`config.php:35`) the page loads the
  gitignored `*.min.js` (`application/helpers/asset_helper.php:31-33`). These files are rebuilt only by
  `bash scripts/test.sh` (`scripts/test.sh:65-77`) or by hand. After `git checkout` the page keeps serving the
  previous branch's JS.
- **Browser cache.** Every asset URL carries the same fixed cache-busting token (`application/config/app.php:18`,
  `application/helpers/asset_helper.php:29`). After a rebuild the browser may keep the old file. The user saw the
  old wizard scroll behaviour until a hard reload.
- **No reusable Booking-Flow measurement.** `docs/visual-measurement.md:13-15` sets up Playwright by hand in
  `/tmp` and only measures navbar, hero and footer. For 0020 the whole Booking-Flow walk (selectors, required
  fields, consent checkboxes, fade timing) had to be written from scratch.
- **Upstream rule silent on JS.** "Upstream files only call fork code" names controllers, libraries and views
  (`CODING_STANDARDS.md:9-12`). Fork logic went into the upstream page script, and only the review moved it out.
- **Missing red evidence until review.** No implementer of 0020 or 0021 recorded a `Rot vor Fix:` line.
  `check:scratch` only requires it from `needs-verification` on (`CODING_STANDARDS.md:52`), and ticket checklists
  have no item for it.
- **Merge conflicts between parallel specs.**
  - Two specs both added a section `## 10` to `docs/manual-tests.md` (`docs/manual-tests.md:206` on each branch).
  - Both changed neighbouring rows of the generated `.scratch/INDEX.md`.
- **Composer in a worktree hits the main checkout.** The documented command
  `docker compose exec -T php-fpm composer scratch:index` (`docs/agents/issue-tracker.md:50-51`) runs in the
  container's working directory, which is the main checkout. In a worktree under `.claude/worktrees/` it silently
  rewrites the main checkout's files. An implementer found the `-w` workaround by trial.
- **Agent worktrees start from `main`.** Agent worktrees branched from `origin/HEAD`, which pointed to
  `origin/main`. This has been fixed locally (`git remote set-head origin staging`), but it is not documented
  anywhere.
- **Reproductions touch real local data.** To reproduce a refusal, the agent booked with the user's own email. That
  created an appointment, sent three mails and overwrote name and phone of the user's customer record, which had to
  be restored by hand. The smoke fixtures already create and remove their own test customers
  (`tests/smoke/fixtures.php`), but nothing points measurement or reproduction scripts to them.
- **Browser timezone differs from the user's.** The Playwright container runs in UTC, and the booking page takes the
  browser's timezone (`assets/js/pages/booking.js:221-224`). The first reproduction succeeded where the user's
  booking failed, because the hours were shifted by two hours.
- **nginx requests missing from `docker compose logs`.** The access log is written to a custom file
  (`docker/nginx/nginx.conf:8`) instead of the image's default path, which the image sends to the container output.
  `docker compose logs nginx` therefore shows no requests.

## Solution

- After any branch switch, the page serves the JavaScript of the checked-out branch, and the browser loads it
  without a hard reload.
- One command walks the Booking-Flow in a real browser and reports the Wizard-Card position after every step,
  for both viewports.
- Parallel specs merge without conflicts in the manual test guide and the index.
- Composer commands work the same in the main checkout and in a worktree.
- Implementers record red evidence before review.

## User Stories

1. As the salon owner testing a branch locally, I want the page to run that branch's JavaScript right after
   `git checkout`, so that I do not report bugs that are already fixed.
2. As the salon owner, I want the browser to pick up a rebuilt script on a normal reload, so that I do not need
   to know about hard reloads.
3. As the salon owner, I want production visitors to get new scripts after a deploy without a stale cache, so
   that a fix reaches customers immediately.
4. As an agent, I want stale scripts rebuilt automatically after a checkout, so that Playwright measures the
   code I am looking at.
5. As an agent, I want the checkout hook to stay quiet and fast when no script changed, so that switching
   branches is not slowed down.
6. As an agent, I want a checked-in Playwright setup with a pinned version, so that I do not install it by
   hand in `/tmp` every session.
7. As an agent, I want a Booking-Flow measurement command that clicks through all steps and back, so that
   layout and scroll specs can be verified with one call.
8. As an agent, I want the measurement to take viewport and theme as parameters, so that desktop, phone and
   both themes are covered without editing code.
9. As the salon owner, I want CI and deploy unaffected by the Playwright dependency, so that builds stay fast
   and nothing test-only reaches the server.
10. As a reviewer, I want the upstream rule to cover page scripts explicitly, so that fork JS logic lands in a
    fork-owned module the first time.
11. As an agent writing a spec, I want to know that fork JS rules live in fork-owned utility modules, so that
    the spec does not prescribe the upstream file.
12. As an implementer, I want every ticket checklist to include the red-evidence item, so that I record it
    before handing over.
13. As two agents working in parallel, we want manual test sections named after their spec, so that our
    sections never collide.
14. As the person merging, I want the generated index to resolve itself on merge, so that I only regenerate it.
15. As an agent in a worktree, I want one composer command that runs against my worktree, so that I never
    change the main checkout by accident.
16. As an agent, I want the docs to explain that agent worktrees branch from `origin/HEAD`, so that I can
    recognise and fix a wrong base.
17. As the salon owner, I want measurement and reproduction runs to leave my local customers, appointments and
    mails untouched, so that my own test data stays valid.
18. As an agent, I want the measurement to run in the salon's timezone and locale by default, so that I see the
    same hours as the user.
19. As an agent, I want `docker compose logs nginx` to show the requests, so that I can see what the browser sent
    without extra knowledge.

## Implementation Decisions

- **Shared staleness detection.** The "source newer than its minified file" check moves out of the test script
  into the shared shell library, and the test script uses it from there. A new `post-checkout` hook next to the
  existing pre-commit hook uses the same check:
  - it runs only on branch checkouts;
  - it rebuilds scripts only when one is stale and `node_modules` exists;
  - it prints one line when it rebuilds and nothing otherwise;
  - a failed build only warns and never blocks the checkout.
- **Cache-busting token per file.** The asset URL token is derived from the modification time of the file
  actually served (minified or not). When the file cannot be read, the configured token is the fallback. The
  decision lives in a fork-owned helper or rule that the upstream asset helper calls (upstream rule, ADR 0005).
  Production benefits as well: the deploy rewrites changed files, so their token changes.
- **Playwright.**
  - Exact devDependency `playwright@1.53.0` (no range), matching the Docker image
    `mcr.microsoft.com/playwright:v1.53.0-noble`.
  - Browsers come from the image. The npm package installs no browsers, so CI and deploy (`npm ci`) only grow
    by the package.
  - A Booking-Flow measurement script and an npm script run it in the image against the local stack.
  - `docs/visual-measurement.md` moves its setup from `/tmp` to the checked-in script and documents the new
    command.
  - Playwright does not run in CI (it needs the stack).
  - The measurement creates its own test customer and appointments through the smoke fixtures and removes them
    afterwards, also when a run fails. It never uses existing customers.
  - Browser timezone `Europe/Berlin` and locale `de-DE` by default, both overridable by parameter.
- **Coding standards.** The upstream rule also names page scripts. Fork JS rules live in fork-owned utility
  modules that the page script calls. The development conventions mention this where specs are written.
- **Red evidence.**
  - The issue-tracker doc defines that every feat/fix ticket checklist ends with a `Rot vor Fix:` item.
  - `check:scratch` additionally fails when a ticket of spec 0022 or later has every implementation checkbox
    ticked but no `Rot vor Fix:` line. The user's visual-check item does not count as an implementation item.
- **Manual test sections.** Sections in the manual test guide are headed by their spec number (e.g.
  `## 0020 Wizard step scroll`) instead of a running number. Existing sections are renamed; the preparation and
  basic-run sections keep a stable name without a number.
- **Generated index.**
  - `.scratch/INDEX.md` gets a merge attribute that keeps the current side; regenerating after the merge is
    documented and enforced by `check:scratch` in CI.
  - The merge driver is registered by the same setup step that activates the hooks path, and that step is
    documented.
- **Composer wrapper.**
  - A script runs `composer <args>` inside `php-fpm` with the working directory of the current checkout (main
    or worktree), built on the existing stack library.
  - Docs that show `docker compose exec -T php-fpm composer …` for repository commands point to the wrapper.
- **Worktree base.** The git-workflow doc states that agent worktrees branch from `origin/HEAD` and that it must
  point to `staging` (`git remote set-head origin staging`).
- **nginx logs.** The access and error logs use the image's default paths, so they appear in
  `docker compose logs nginx`. Request bodies are not logged (personal data).

## Testing Decisions

- A good test checks observable behaviour, for example the URL a view gets or the exit code of a check, not
  internal steps.
- **Cache-busting token.** PHPUnit tests of the fork-owned token rule: an existing file gives its modification
  time, a missing file gives the configured token, and a minified and a non-minified path are each resolved to
  the file actually served. Prior art: `tests/Unit/AssetReferenceValidatorTest.php`.
- **Red-evidence check.** PHPUnit cases in the existing scratch index tests: a fully ticked ticket without the
  line fails, the same ticket with the line passes, and a ticket from before 0022 is ignored. Prior art:
  `tests/Unit/ScratchIndexTest.php`.
- **Hook, wrapper, Playwright script, merge driver.** No automated tests. Verify once by hand and record the
  result in the ticket comments:
  - checkout between `staging` and a branch with a JS change → rebuild line and new code served;
  - the wrapper in a worktree changes only the worktree;
  - the measurement command reproduces the 0020 table, and afterwards no test customer, appointment or customer
    change is left in the database;
  - a booking request shows up in `docker compose logs nginx`;
  - merging two branches that both touch the index leaves no conflict markers.
- `bash scripts/test.sh` stays the single validation entry point.

## Out of Scope

- Running Playwright in CI.
- A JavaScript bundler or test framework.
- Changing which assets are minified or how the deploy builds them.
- Timezone configuration of the salon (separate question, see Further Notes).

## Further Notes

- Origin: retro of the 0020/0021 run and the follow-up discussion on 2026-10-16.
- `origin/HEAD` was already switched to `staging` locally on 2026-10-16; only the doc line is open.
- A simpler native scroll implementation for 0020 is not part of this spec; it belongs to spec 0020 if it is done.
