# 01: Keep the Wizard-Card in view on step changes

**What to build:** After "next" or "back" in the Booking-Flow, the customer sees the Wizard-Card of the new step
without scrolling. If the Wizard-Card is already fully visible, nothing moves; otherwise the page puts its top about
16 px below the top of the viewport, instantly. A Wizard-Card taller than the viewport shows its top. The page does
not jump while the steps fade, and the initial page load (including the automatic "next" for preselected links)
stays unchanged. Same behaviour in both themes and in the Manage-Link flow.

**Blocked by:** None (can start immediately)

**Status:** ready-for-agent

- [ ] Scroll rule as a small pure function without DOM access (Wizard-Card position and height, viewport height,
      current scroll position → target position or "no scroll"); it replaces the scroll-to-top of "next" and is also
      applied on "back"
- [ ] No height collapse between fading out the old step and fading in the new one
- [ ] The automatic "next" on page load does not scroll
- [ ] Playwright check per `docs/visual-measurement.md`: desktop 1280×800 and phone 390×844, theme `mebeauty` and
      `default`, "next" and "back", a step taller than the viewport, a preselected service/provider link; the
      measured Wizard-Card edges are noted in the ticket comment
- [ ] Manual test cases in `docs/manual-tests.md` (new section referencing 0020/01)
- [ ] Visual check by the user (ends as `needs-verification`)
- [ ] `bash scripts/test.sh` green

## Comments
