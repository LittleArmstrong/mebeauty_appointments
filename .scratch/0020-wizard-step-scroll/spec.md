# Keep the Wizard-Card in view when changing booking steps

Status: ready-for-agent
Created: 2026-10-16
Closed: –
Priority: P2
Depends-on: –

## Problem Statement

On every "next" step of the Booking-Flow the page scrolls to the very top
(`assets/js/pages/booking.js:487-491`). With the `mebeauty` theme the Site-Chrome (navbar and intro with hero image)
sits above the Wizard-Card (`application/views/layouts/booking_layout.php:45-52`), so after each step the customer
sees navbar and hero, the Wizard-Card is cut off at the bottom, and they have to scroll down again to continue.
Found in manual test T-B-01 (2026-10-16).

The "back" button does not scroll at all (`assets/js/pages/booking.js:500-512`): after scrolling down to the button
of a tall step, the customer lands somewhere in the middle of the previous step.

Steps are switched by fading the old step out and the new one in afterwards
(`assets/js/pages/booking.js:480-485`); in between, neither step is displayed, the page gets shorter and the browser
may clamp the scroll position on its own.

## Solution

After a step change, the customer sees the Wizard-Card of the new step without having to scroll: if it is already
fully visible, nothing moves; otherwise the page puts the top of the Wizard-Card just below the top of the screen.
If the Wizard-Card is taller than the screen, its top is visible and the rest is reached by scrolling down as
usual. The page no longer jumps while the steps fade.

## User Stories

1. As a customer, I want to see the whole Wizard-Card after clicking "next", so that I can continue without
   scrolling down past the navbar and hero image.
2. As a customer, I want the Wizard-Card to stay where it is when it is already fully visible, so that the page does
   not jump without reason.
3. As a customer on a phone, I want the top of the next step to be visible after clicking "next" at the bottom of a
   long step, so that I start reading the new step from its beginning.
4. As a customer, I want "back" to behave the same as "next", so that I see the beginning of the previous step.
5. As a customer, I want the page not to flicker or jump while the steps fade, so that the transition feels calm.
6. As a customer opening the booking page, I want to see the navbar and hero image first as before, so that the
   page still looks like the mebeauty website.
7. As a customer following a booking link with a preselected service and provider, I want the page to open at the
   top as before, even though the wizard skips the first step on its own.
8. As a customer, I want the step change to happen instantly without a scroll animation, so that I can continue
   right away.
9. As a salon owner using the `default` theme, I want the same behaviour, so that customers have a consistent
   experience regardless of the theme.
10. As a customer rescheduling via the Manage-Link, I want the same step behaviour as when booking.

## Implementation Decisions

- **Scroll rule**, evaluated after the new step is displayed, for "next" and "back":
    1. The Wizard-Card (top and bottom edge, including its footer) is fully inside the viewport → no scrolling.
    2. Otherwise scroll so that the top of the Wizard-Card is about 16 px below the top of the viewport. If the
       Wizard-Card fits into the viewport, it is then fully visible; if it is taller, its top is at the top.
    3. Instant, no smooth scrolling.
- The rule is a small pure function without DOM access: it takes the Wizard-Card's position and height, the viewport
  height and the current scroll position, and returns the target scroll position or "no scroll". The booking page
  script only measures and applies the result. The existing scroll-to-top is replaced.
- The page height must not collapse during the step change, e.g. by keeping the Wizard-Card's height while the old
  step is faded out, or by switching steps without a gap in which neither is displayed. The choice is left to the
  implementation as long as no intermediate jump is visible.
- The automatic "next" on page load (preselected service and provider, or only one service and provider) must not
  scroll; the initial page load stays unchanged.
- One implementation for both themes; the success page after booking is a separate page and unchanged.
- The change is in the upstream booking page script; follow the surrounding upstream style (ADR 0005).

## Testing Decisions

- No automated JavaScript tests exist in the repository, and a test framework only for this rule is not worth it.
  Layout is checked visually by the user (AGENTS.md).
- Before handing over, the agent checks the behaviour once with Playwright as described in
  `docs/visual-measurement.md`: desktop (1280×800) and phone width (390×844), theme `mebeauty` and `default`,
  "next" and "back", including a step taller than the viewport, and the preselected-link case. Measure the
  Wizard-Card's top and bottom edge relative to the viewport after each step change.
- New manual test cases in `docs/manual-tests.md` covering the user stories above.

## Out of Scope

- Scrolling on the initial page load and on the success page.
- Changing the fade animation itself beyond preventing the height collapse.
- Scroll behaviour of the backend pages.

## Further Notes

- Origin: grilling session on 2026-10-16 after manual test T-B-01.
- Glossary: Booking-Flow, Wizard-Card, Site-Chrome, Manage-Link.
