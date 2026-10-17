# 05: Review rules, spec convention for server responses, measurement option form

**What to build:** `CODING_STANDARDS.md` gets two rules: global scripts (`assets/js/app.js`,
`assets/js/utils/message.js`) use only what the installation page also loads, with dynamic text escaped by the
server; a script that creates test data stores the raw setup output before anything else can fail and cleans up from
it. `docs/agents/development-conventions.md` asks spec writers to state status code and fields when a spec changes a
server response. The booking measurement accepts `--name value` as well as `--name=value`.

**Blocked by:** None (can start immediately)

**Status:** ready-for-agent

- [x] Two rules in `CODING_STANDARDS.md`
- [x] Spec-writing note in `docs/agents/development-conventions.md`
- [x] Measurement accepts both option forms; header comment updated
- [x] Manual check recorded: `--viewport 390x844` and `--viewport=390x844` give the same table
- [x] `Rot vor Fix:` line recorded (`entfällt – <reason>` is fine for docs and tooling)
- [x] `bash scripts/test.sh` green

## Comments

- 2026-10-18 (implementer): `CODING_STANDARDS.md` gains "Global scripts use only what every page loads" and "Test
  data cleanup works from the raw setup output"; `docs/agents/development-conventions.md` asks a spec that changes a
  server response to state status code and response fields. `tests/playwright/booking-flow.js` takes the next
  argument as the value when an option has no `=` (not for `--json`, not when the next argument is an option);
  header comment and `docs/visual-measurement.md` name both forms.
- Rot vor Fix: `npm run measure:booking -- --viewport 390x844 --theme=mebeauty` (old parser) – exit 1,
  `measure-booking-flow: Unknown option --viewport (see the header of tests/playwright/booking-flow.js)`.
- Manual check after the fix: `--viewport 390x844 --theme mebeauty` and `--viewport=390x844 --theme mebeauty` both
  exit 0 with the same row `| mebeauty 390×844 | 428/1263, y 0 | 16/1145 | 16/1211 | 16/867 | 16/1211 (no move) |
  16/1145 | 16/851 | step 2, y 0, top 428 |` (matches the last run in `docs/visual-measurement.md`).
- `bash scripts/test.sh` in the worktree: exit 3 (phpunit, all checks OK; smokes skipped in the worktree).
