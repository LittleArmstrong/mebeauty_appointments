# 01: Backend logo and company name link to the calendar

**What to build:** In the backend header, clicking the logo, the company name or the subtitle opens the calendar,
with the same target as the "Calendar" menu item (including the table view variant). The header looks unchanged,
the link has an accessible name, and it works at phone width next to the collapsed menu button.

**Blocked by:** None (can start immediately)

**Status:** ready-for-agent

- [x] The header brand is one link to the calendar; its target and the "Calendar" menu item's target come from one
      shared expression
- [x] No permission check (every role that can log in may view the calendar)
- [x] Appearance unchanged (no underline, no colour change), accessible name set
- [x] Manual test case in `docs/manual-tests.md` (referencing 0021/01): default view, table view, phone width
- [ ] Visual check by the user (ends as `needs-verification`)
- [x] `bash scripts/test.sh` green

## Comments

- 2026-10-16 (agent, branch `agent/0021-01-impl`): `backend_header.php` computes `$calendar_url` once (with the
  `?view=table` variant) and uses it for both the "Calendar" menu item and the header brand. `#header-logo` is now
  an `<a class="navbar-brand">` instead of a `<div>` (Bootstrap's `.navbar-brand` removes the underline; the
  children keep their explicit `text-white` colours) with `aria-label` "<company name> - <Calendar>". No permission
  check. No automated test (single view link, per spec). Manual cases T-BH-01..03 added to `docs/manual-tests.md`
  (section 10). `bash scripts/test.sh` exit 3 in the worktree (all checks green, smokes skipped).
