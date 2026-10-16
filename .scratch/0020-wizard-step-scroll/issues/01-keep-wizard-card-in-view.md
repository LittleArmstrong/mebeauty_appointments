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
- [x] Playwright check per `docs/visual-measurement.md`: desktop 1280×800 and phone 390×844, theme `mebeauty` and
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

### 2026-10-16 — Playwright measurement (main checkout, integration branch)

Wizard-Card `#book-appointment-wizard` edges relative to the viewport after each change (top/bottom px, viewport
height in brackets). Each "next"/"back" was clicked after scrolling its button into view.

| Theme / viewport | Load | 1→2 | 2→3 | 3→4 | 4→3 | 3→2 | 2→1 | Preselected link |
|---|---|---|---|---|---|---|---|---|
| mebeauty 1280×800 | 408/1277, y 0 | 16/885 | 16/910 | 16/885 | 16/910 | 16/885 | 16/885 | step 2, y 0, top 408 |
| mebeauty 390×844 | 428/1263, y 0 | 16/1145 | 16/1211 | 16/851 | 16/1211 | 16/1145 | 16/851 | step 2, y 0, top 428 |
| default 1280×800 | 16/891, y 0 | 16/891 (no move) | 16/936 (no move) | 16/891 | 16/936 (no move) | 16/891 | 16/891 (no move) | step 2, y 0 |
| default 390×844 | 1/843, y 0 | 0/1142 (no move) | 0/1243 | 1/843 | 0/1243 (no move) | 0/1142 | 1/843 | step 2, y 0 |

- Default theme: the card sits at the top of the page, so the target clamps to 0 and the page returns to the top.
- Fully visible case: mebeauty 1280×1000, card at 60/929 before "next" → 60/929 after, no scroll.
- During the fade, scrollY was sampled every 20 ms: it drifts by at most 3 px (e.g. 764→762) and then jumps once to
  the target; there is no collapse to the top.
