# 03: Upstream rule for page scripts, red-evidence item, conflict-free manual tests and index

**What to build:** The upstream rule in `CODING_STANDARDS.md` names page scripts and points fork JS rules to
fork-owned utility modules; the development conventions mention it for spec writers. Every feat/fix ticket
checklist gets a `Rot vor Fix:` item (issue-tracker doc), and `check:scratch` fails for a ticket of spec 0022 or
later whose implementation items are all ticked but has no `Rot vor Fix:` line. Manual test sections are named by
spec number. `.scratch/INDEX.md` keeps the current side on merge via a merge attribute, registered by the
documented setup step that also activates the hooks path.

**Blocked by:** None (can start immediately)

**Status:** resolved

- [x] Upstream rule covers page scripts; conventions note for spec writers
- [x] Issue-tracker doc: `Rot vor Fix:` item in every feat/fix checklist
- [x] `check:scratch` rule with PHPUnit cases (missing line fails, present passes, pre-0022 ignored)
- [x] Manual test sections renamed to spec numbers; references updated
- [x] Merge attribute and driver registration for the index, documented with the hooks setup
- [x] Manual check recorded: merging two branches that both touch the index leaves no conflict markers
- [x] `Rot vor Fix:` line recorded
- [x] `bash scripts/test.sh` green

## Comments

- Rot vor Fix: `ScratchIndexTest::testFullyTickedTicketWithoutRedEvidenceIsReportedFromSpec0022` – against the old
  `scripts/scratch-index.php`: `Failed asserting that '…' contains "0022-feature/issues/01-a.md: alle
  Umsetzungspunkte abgehakt, aber Beleg "Rot vor Fix:" fehlt"`. Mutation check for
  `testUserCheckAndRedEvidenceItemsDoNotCountAsImplementationItems`: with the "by the user|durch den Nutzer"
  exclusion disabled it fails with `… contains "0022-feature/issues/01-a.md: alle Umsetzungspunkte abgehakt"`.
  The other two new cases (line present or item open passes, pre-0022 ignored) are guards that pass on both.
- Rule details: implementation items are all `- [ ]`/`- [x]` items except the `Rot vor Fix:` item and the user's
  check (recognised by "by the user" / "durch den Nutzer"); a ticket without implementation items never triggers.
  Tickets in `needs-verification`/`resolved` keep the existing 0017 error, so there is no double message. The open
  0022 tickets (unticked) pass `check:scratch`.
- Manual check, index merge (2026-10-17, throwaway branches `tmp-0022-03-a/b/c` in the worktree, deleted
  afterwards): `a` changed the 0021 row, `b` the neighbouring 0022 row of `.scratch/INDEX.md`. Merging `a` into a
  copy of `b` with the driver unset → `CONFLICT (content): Merge conflict in .scratch/INDEX.md`. With
  `git -c merge.scratch-index.driver=true merge` → `Merge made by the 'ort' strategy.`, 0 conflict markers, current
  side kept (0022 row `in-progress`, 0021 row unchanged); regenerate afterwards as documented.
- The driver is not registered in this clone's git config yet (local config, not changed by the agent): run
  `git config merge.scratch-index.driver true` once (`docs/agents/validation-and-completion.md`).
- Manual test headings are numbered by the introducing spec. Slot capacity and manage link both come from 0006 and
  share the prefix; the footer section is 0019 (0014 is already in the theme section). Section numbers quoted in
  older ticket comments (0018, 0020/01, 0021/01) are historical records and stay unchanged.
- `bash scripts/test.sh` in the worktree: exit 3 (PHPUnit 218 tests OK, all checks OK, smokes skipped in the
  worktree).
