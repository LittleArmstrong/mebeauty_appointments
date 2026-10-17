# 02: Checked-in Playwright and a Booking-Flow measurement command

**What to build:** `playwright@1.53.0` as an exact devDependency, a checked-in script that walks the Booking-Flow
(next through all steps, then back) and reports the Wizard-Card edges, scroll position and step after each change,
parameterised by viewport and theme, and an npm script that runs it in the pinned Playwright Docker image against
the local stack. `docs/visual-measurement.md` uses the checked-in setup instead of `/tmp`. Each run creates its own test customer
and appointments through the smoke fixtures and removes them afterwards, and the browser runs in `Europe/Berlin` /
`de-DE` by default.

**Blocked by:** None (can start immediately)

**Status:** resolved

- [x] Exact devDependency, no browser download in `npm ci`; CI and deploy unchanged otherwise
- [x] Measurement script with viewport and theme parameters, also covering a preselected service/provider link
- [x] Own test customer via `tests/smoke/fixtures.php`, removed afterwards (also on failure); existing customers
      never used
- [x] Browser timezone `Europe/Berlin` and locale `de-DE` by default, overridable
- [x] npm script runs it in `mcr.microsoft.com/playwright:v1.53.0-noble` with host networking
- [x] `docs/visual-measurement.md` updated (setup, command, how to read the output)
- [x] Manual check recorded: the command reproduces the 0020/01 measurement table and leaves no test data behind
- [x] `Rot vor Fix:` line recorded (`entfällt – <reason>` is fine for pure tooling)
- [x] `bash scripts/test.sh` green

## Comments

### 2026-10-17 — implementation (agent/0022-02-booking-flow-measurement)

- `package.json`: `playwright` pinned to exactly `1.53.0` (devDependency) and `npm run measure:booking`. The package
  has no install script, so `npm ci` downloads no browsers (checked: no `scripts` in `playwright`/`playwright-core`;
  the only lock entry with an install script is the macOS-only optional `fsevents`). CI and deploy workflows unchanged.
- `scripts/measure-booking-flow.sh`: creates the test data with `tests/smoke/fixtures.php setup` (two `smoke-…@example.com`
  customers with one appointment each, plus provider, service and a date with free slots), removes them in an EXIT
  trap with `fixtures.php cleanup`, and runs the walk in `mcr.microsoft.com/playwright:v1.53.0-noble` with
  `--network host` (main checkout mounted under its host path, so a worktree's `node_modules` link resolves).
- `tests/playwright/booking-flow.js`: load, next 1→2→3→4, back 4→3→2→1, preselected `?service=&provider=` link;
  per change Wizard-Card top/bottom, scrollY, active step, moved/not moved. Options `--viewport`, `--theme`
  (both repeatable, default both viewports × both themes), `--timezone` (default `Europe/Berlin`), `--locale`
  (default `de-DE`), `--json`. Fills only the required customer fields with the fixture customer, never submits.
- `docs/visual-measurement.md`: checked-in setup instead of `/tmp`, the command, how to read the output, ad-hoc
  scripts run against the repo's `node_modules`.
- Rot vor Fix: entfällt – pure tooling (measurement script, npm script, docs), no behaviour of the app changes; the spec
  asks for no automated test of the Playwright script.
- `bash scripts/test.sh` in the worktree: exit 3 (PHPUnit and all checks green, smokes skipped in the worktree).

### 2026-10-17 — manual check

- `npm run measure:booking` against the local stack (integration branch) reproduces the 0020/01 table: every top
  (16 after a move, clamped 0/1 in `default` on the phone), load and preselected rows identical. Only the step-4
  bottom on mebeauty 390×844 differs (867 vs 851: the confirm frame shows the fixture customer's data). The table
  is in `docs/visual-measurement.md`. The rows also mark `(no move)` where 0020/01 left it implicit.
- Test data: users and appointments (count, max id, MD5 over id/name/email/phone/update time resp.
  id/start/customer/update time) identical before and after three runs, one of them failing on purpose
  (`--timezone=Invalid/Zone`, exit 1): 5 users, 5 appointments, 0 smoke customers. Mailpit total unchanged (61).
- `--viewport=1280x1000 --theme=mebeauty --timezone=UTC --locale=en-US --json` prints one line per change;
  an unknown option exits 1 with a message.

### 2026-10-17 — review fix

- `scripts/measure-booking-flow.sh` stores the raw `fixtures setup` output before anything can fail; the EXIT trap
  derives the IDs from it, so a setup that prints its JSON and then exits non-zero, or a failing parse, still
  cleans up. Checked with the script's setup/cleanup block and a stubbed `fixtures` (Playwright is not installed in
  the main checkout's `node_modules` yet): ok → cleanup `11,12 21,22`; JSON then exit 1 → cleanup `11,12 21,22`;
  exit 1 without JSON → no cleanup call, exit 2.
- `field()` reads a dotted path with `node -p` (no host python3, no interpolated code); `IMAGE` derives from
  `VERSION` (package.json stays the pin); comment on why the stack check is inline; file mode 755.
