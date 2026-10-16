# Backend header logo links to the calendar

Status: needs-verification
Created: 2026-10-16
Closed: –
Priority: P2
Depends-on: –

## Problem Statement

In the backend, the logo and company name at the top left are not clickable
(`application/views/components/backend_header.php:14-18`). Staff expect them to lead back to the start page, as on
most web applications, and have to use the "Calendar" menu item instead. The start page after login is the calendar
(`application/controllers/Login.php:47`). Found during the manual test run on 2026-10-16.

## Solution

Clicking the logo or the company name in the backend header opens the calendar, exactly like the "Calendar" menu
item.

## User Stories

1. As a staff member, I want to click the logo in the backend header to get to the calendar, so that I can return to
   the start page from anywhere.
2. As a staff member, I want the company name next to the logo to be clickable as well, so that I do not have to aim
   at the image.
3. As a staff member who uses the table view of the calendar, I want the logo to open the table view, as the
   "Calendar" menu item does.
4. As a staff member, I want the header to look unchanged (no underline, no colour change), so that the branding
   stays as it is.
5. As a keyboard user, I want the logo link to be reachable and announced as a link to the calendar.
6. As a staff member on a phone, I want the logo link to work next to the collapsed menu button.

## Implementation Decisions

- The header brand (logo, company name and subtitle) becomes one link whose target is built the same way as the
  "Calendar" menu item's target, including the table view variant; ideally both use one shared expression so they
  cannot drift apart.
- No permission check: every role that can log in (admin, provider, secretary) may view the calendar, and the
  calendar itself rejects users without access.
- The visual appearance stays the same (link styling neutralised); the link gets an accessible name.
- The change is in an upstream view; follow the surrounding upstream style (ADR 0005).

## Testing Decisions

- No automated test: it is a single link in a view, and backend layout is checked visually by the user (AGENTS.md).
- New manual test case in `docs/manual-tests.md`: clicking logo and company name opens the calendar, also with the
  table view setting and at phone width.

## Out of Scope

- The logo of the booking page and of the account/login pages.
- Changing the start page after login.

## Further Notes

- Origin: grilling session on 2026-10-16 after the manual test run.
