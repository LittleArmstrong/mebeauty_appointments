# 02: Checked-in Playwright and a Booking-Flow measurement command

**What to build:** `playwright@1.53.0` as an exact devDependency, a checked-in script that walks the Booking-Flow
(next through all steps, then back) and reports the Wizard-Card edges, scroll position and step after each change,
parameterised by viewport and theme, and an npm script that runs it in the pinned Playwright Docker image against
the local stack. `docs/visual-measurement.md` uses the checked-in setup instead of `/tmp`.

**Blocked by:** None (can start immediately)

**Status:** ready-for-agent

- [ ] Exact devDependency, no browser download in `npm ci`; CI and deploy unchanged otherwise
- [ ] Measurement script with viewport and theme parameters, also covering a preselected service/provider link
- [ ] npm script runs it in `mcr.microsoft.com/playwright:v1.53.0-noble` with host networking
- [ ] `docs/visual-measurement.md` updated (setup, command, how to read the output)
- [ ] Manual check recorded: the command reproduces the 0020/01 measurement table
- [ ] `Rot vor Fix:` line recorded (`entfällt – <reason>` is fine for pure tooling)
- [ ] `bash scripts/test.sh` green

## Comments
