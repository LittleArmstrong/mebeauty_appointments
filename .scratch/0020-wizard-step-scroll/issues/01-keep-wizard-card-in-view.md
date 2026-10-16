# 01: Keep the Wizard-Card in view on step changes

**What to build:** After "next" or "back" in the Booking-Flow, the customer sees the Wizard-Card of the new step
without scrolling. If the Wizard-Card is already fully visible, nothing moves; otherwise the page puts its top about
16 px below the top of the viewport, instantly. A Wizard-Card taller than the viewport shows its top. The page does
not jump while the steps fade, and the initial page load (including the automatic "next" for preselected links)
stays unchanged. Same behaviour in both themes and in the Manage-Link flow.

**Blocked by:** None (can start immediately)

**Status:** ready-for-agent

- [x] Scroll rule as a small pure function without DOM access (Wizard-Card position and height, viewport height,
      current scroll position → target position or "no scroll"); it replaces the scroll-to-top of "next" and is also
      applied on "back"
- [x] No height collapse between fading out the old step and fading in the new one
- [x] The automatic "next" on page load does not scroll
- [ ] Playwright check per `docs/visual-measurement.md`: desktop 1280×800 and phone 390×844, theme `mebeauty` and
      `default`, "next" and "back", a step taller than the viewport, a preselected service/provider link; the
      measured Wizard-Card edges are noted in the ticket comment
- [x] Manual test cases in `docs/manual-tests.md` (new section referencing 0020/01)
- [ ] Visual check by the user (ends as `needs-verification`)
- [x] `bash scripts/test.sh` green

## Comments

### 2026-10-16 — implementation (agent/0020-01-impl)

- `assets/js/pages/booking.js`: new pure `getWizardScrollTarget(cardTop, cardHeight, viewportHeight, scrollTop)`
  (returns target scroll position or `null`, offset 16 px) and `changeWizardStep()` used by "next" and "back". It
  pins `#book-appointment-wizard` to its current height (`min-height`) while the old frame fades out, removes it
  once the new frame is displayed, then measures the card and scrolls instantly (`behavior: 'instant'`, overrides
  Bootstrap's `scroll-behavior: smooth`). The old scroll-to-top is gone. Programmatic clicks (no
  `event.originalEvent`, i.e. the automatic "next" for preselected links) do not scroll.
- Rule checked with a throwaway node script (fully visible → no scroll; cut off → top at 16 px; taller than the
  viewport → top at 16 px; clamped at 0). No JS test framework added, per spec.
- `docs/manual-tests.md`: section 10, T-WS-01 to T-WS-08.
- `bash scripts/test.sh`: exit 3 in the worktree (all green, smokes skipped). Playwright measurement still open,
  runs from the main checkout after the merge.
