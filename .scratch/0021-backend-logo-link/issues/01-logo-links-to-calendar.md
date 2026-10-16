# 01: Backend logo and company name link to the calendar

**What to build:** In the backend header, clicking the logo, the company name or the subtitle opens the calendar,
with the same target as the "Calendar" menu item (including the table view variant). The header looks unchanged,
the link has an accessible name, and it works at phone width next to the collapsed menu button.

**Blocked by:** None (can start immediately)

**Status:** ready-for-agent

- [ ] The header brand is one link to the calendar; its target and the "Calendar" menu item's target come from one
      shared expression
- [ ] No permission check (every role that can log in may view the calendar)
- [ ] Appearance unchanged (no underline, no colour change), accessible name set
- [ ] Manual test case in `docs/manual-tests.md` (referencing 0021/01): default view, table view, phone width
- [ ] Visual check by the user (ends as `needs-verification`)
- [ ] `bash scripts/test.sh` green

## Comments
