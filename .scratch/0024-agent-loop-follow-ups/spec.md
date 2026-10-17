# Agent-loop follow-ups: current worktree base, smokes in the worktree, local index

Status: ready-for-agent
Created: 2026-10-18
Closed: –
Priority: P2
Depends-on: 0022

## Problem Statement

The retros of the 0022 and 0023 runs (2026-10-17/18) found gaps that cost every implementer time and, in one case,
let a regression through to review.

- **Agent worktrees start from a stale base.** Agent worktrees branch from `origin/HEAD`, i.e. the remote
  `origin/staging`. The user pushes rarely, so on 2026-10-18 `origin/staging` (`027fd0c9`) was 43 commits behind
  local `staging`. Three implementers of the 0022/0023 runs reported a worktree starting at `027fd0c9` and had to
  reset onto the integration branch first. The doc only asks for `origin/HEAD` to point to `staging` (`docs/agents/git-workflow.md:87`),
  which it already does. There is no project `.claude/settings.json`.
- **Smokes are skipped in a worktree.** `scripts/test.sh:100-101` skips every smoke in a worktree and
  `scripts/test.sh:115` exits 3, so red evidence for smoke-tested tickets needs a manual detour. nginx already
  serves worktrees below its root (`docker/nginx/nginx.conf:13`, `root /var/www/html`), at
  `http://localhost/.claude/worktrees/<name>/`. The smokes take `BASE_URL` (`tests/smoke/lib.sh:18`), but their
  fixtures always run in the main checkout (`tests/smoke/lib.sh:27-28`). The three implementers of 0023 each wrote
  their own `docker` shim in their scratchpad to run fixtures in the worktree.
- **The generated index needs a merge driver.** `.scratch/INDEX.md` is committed and generated. Parallel branches
  conflict on it, so 0022 added a merge driver (`.gitattributes:5`) that only works after a manual local setup step
  (`docs/agents/validation-and-completion.md:25`). On 2026-10-18 it was not set up in the main checkout. CI checks
  that the committed index is current (`.github/workflows/ci.yml:53`).
- **Missing npm packages are noticed late.** After 0022/02 added `playwright` to `package.json`, the main checkout's
  `node_modules` lacked it until `npm ci` was run by hand. `scripts/test.sh:65` only checks for gulp, and the
  post-checkout hook stays silent without `node_modules` (`scripts/git-hooks/post-checkout:13`).
- **Review-only findings without a rule.**
  - The global `assets/js/app.js` used `App.Utils.String`, which the installation page does not load
    (`application/views/pages/installation.php:219-220`); only the review found it (0023, review fix `6c740c21`).
  - The measurement script left test data behind when it failed between fixture setup and storing the IDs; only the
    review found it (0022/02, review fix `d4c45683`).
  - 0023/02 changed the HTTP status of booking refusals from 500 to 409, which the spec did not mention; only the
    spec review noticed the contract change.
- **Measurement option form.** `--viewport 390x844` is rejected; only `--viewport=390x844` is accepted
  (`tests/playwright/booking-flow.js:30-32`).

## Solution

- Agent worktrees start from the locally checked-out branch.
- `bash scripts/test.sh` runs every smoke in a worktree against the worktree's own code and exits 0 when green.
- The spec index is generated locally and never committed, so it never conflicts.
- Missing npm packages are reported after a checkout or merge and installed by the test script.
- The reviewer has rules for global scripts and for scripts that create test data; spec writers state changed
  server responses.

## User Stories

1. As an implementer, I want my worktree to start from the branch the orchestrator has checked out, so that I build
   on the current integration branch without a reset.
2. As an implementer in a worktree, I want `bash scripts/test.sh` to run the smokes against my worktree, so that I
   record red evidence for smoke-tested behaviour myself.
3. As the orchestrator, I want a green worktree run to exit 0, so that "green" means the same everywhere.
4. As an implementer, I want the smoke fixtures to run in the code of the checkout being tested, so that new fixture
   commands work before the merge.
5. As the salon owner, I want smoke runs from a worktree to leave my own customers and appointments untouched, so
   that my test data stays valid.
6. As two agents merging in parallel, we want no conflict on the spec index, so that merges need no manual step.
7. As the salon owner, I want to open an up-to-date index in my editor after a checkout or merge, so that I see what
   is open.
8. As an agent, I want one command that prints or regenerates the index, so that I can read it without a file in git.
9. As the salon owner, I want CI to keep validating spec headers and numbering, so that broken specs are caught.
10. As an agent, I want a short note after a checkout or merge when `package-lock.json` changed, so that I know
    `npm ci` is due.
11. As an agent, I want `bash scripts/test.sh` to install missing npm packages itself, so that validation does not
    fail on a missing package.
12. As a reviewer, I want a rule that global scripts use only what every page loads, so that I check the
    installation page.
13. As a reviewer, I want a rule that a script creating test data stores what it created before anything can fail,
    so that cleanup always works.
14. As a spec writer, I want the conventions to ask for status code and fields when a spec changes a server response,
    so that contract changes are decided up front.
15. As an agent, I want the measurement to accept `--viewport 390x844` as well as `--viewport=390x844`, so that
    the usual option form works.

## Implementation Decisions

- **Worktree base.** A checked-in `.claude/settings.json` sets `"worktree": {"baseRef": "head"}` (Claude Code
  setting: worktrees branch from the current local `HEAD`). `docs/agents/git-workflow.md` replaces the `origin/HEAD`
  paragraph with this and with the rule that the orchestrator checks out the integration branch in the main checkout
  before starting implementers. The reset-onto-the-integration-branch check stays as a fallback.
- **Smokes in a worktree.**
  - In a worktree, `scripts/test.sh` runs the smokes with `BASE_URL` set to the worktree's URL below nginx.
  - The smoke fixtures run in `php-fpm` with the working directory of the checkout being tested, built on the
    existing stack library (like `scripts/composer.sh`).
  - The worktree's application must generate its URLs for the worktree path (its `config.php` copy or equivalent).
  - The worktree run exits 0 when green; exit 3 and its mentions (`AGENTS.md`, docs) go away. Running the smokes in
    the main checkout after the merge stays part of the completion steps.
  - The database stays shared. Smokes keep using only their own `smoke-…` fixtures and the leftover purge.
- **Local index.**
  - `.scratch/INDEX.md` is removed from git and ignored. The merge driver, its `.gitattributes` line and its setup
    command are removed from code and docs.
  - The index is regenerated locally by the post-checkout hook, a new post-merge hook and `scripts/test.sh`. The
    hooks stay quiet and never fail when the stack is not running.
  - `check:scratch` keeps validating headers, numbering and the ticket rules, but no longer compares a committed
    index. CI keeps running it.
  - The issue-tracker doc and `AGENTS.md` describe the index as a local, generated file.
- **npm packages.**
  - The post-checkout and post-merge hooks print one line when `package-lock.json` is newer than
    `node_modules/.package-lock.json`, and nothing otherwise. They do not install.
  - `scripts/test.sh` runs `npm ci` itself in that case (main checkout only; a worktree links the main checkout's
    `node_modules`).
- **Rules.**
  - `CODING_STANDARDS.md`: global scripts (`assets/js/app.js`, `assets/js/utils/message.js`) use only what the
    installation page also loads; dynamic text arrives escaped from the server.
  - `CODING_STANDARDS.md`: a script that creates test data stores the raw setup output before anything else can
    fail, and its cleanup works from that output.
  - `docs/agents/development-conventions.md` (spec writing): a spec that changes a server response states the status
    code and the response fields.
- **Measurement options.** The measurement accepts both `--name value` and `--name=value`.

## Testing Decisions

- A good test checks observable behaviour: the exit code and output of `scripts/test.sh` or `check:scratch`, the URL
  a smoke requests, not internal steps.
- **`check:scratch` without the committed index.** PHPUnit cases in the existing scratch index tests: a missing or
  stale `INDEX.md` no longer fails the check, header and numbering errors still do. Prior art:
  `tests/Unit/ScratchIndexTest.php`.
- **Measurement options.** Verified by hand: both forms give the same table.
- **Settings, test script, hooks.** No automated tests. Verify once by hand and record the result in the ticket
  comments:
  - an agent worktree started while a non-default branch is checked out has that branch's tip as base;
  - `bash scripts/test.sh` in a worktree runs every smoke against the worktree (a smoke that only passes with the
    worktree's change proves it) and exits 0;
  - after a smoke run from a worktree no `smoke-…` customer is left and the user's customers are unchanged;
  - merging two branches that both add a spec leaves no conflict and an index listing both;
  - a checkout across a `package-lock.json` change prints the npm line, and `scripts/test.sh` installs.
- `bash scripts/test.sh` stays the single validation entry point.

## Out of Scope

- A separate Docker Compose project and database per worktree (`.scratch/IDEAS.md`).
- Pushing `agent/*` branches so that CI runs the smokes (`.scratch/IDEAS.md`).
- Moving the spec tracker to GitHub Issues or Linear.
- An ESLint setup for page globals.

## Further Notes

- Origin: retros of the 0022 and 0023 runs on 2026-10-17/18 and the follow-up discussion with the user.
- The user works alone on the project and has a limited number of GitHub Actions minutes, which is why the local
  worktree smokes are preferred over CI as the gate.
