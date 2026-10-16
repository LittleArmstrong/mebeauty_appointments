# 03: Upstream rule for page scripts, red-evidence item, conflict-free manual tests and index

**What to build:** The upstream rule in `CODING_STANDARDS.md` names page scripts and points fork JS rules to
fork-owned utility modules; the development conventions mention it for spec writers. Every feat/fix ticket
checklist gets a `Rot vor Fix:` item (issue-tracker doc), and `check:scratch` fails for a ticket of spec 0022 or
later whose implementation items are all ticked but has no `Rot vor Fix:` line. Manual test sections are named by
spec number. `.scratch/INDEX.md` keeps the current side on merge via a merge attribute, registered by the
documented setup step that also activates the hooks path.

**Blocked by:** None (can start immediately)

**Status:** ready-for-agent

- [ ] Upstream rule covers page scripts; conventions note for spec writers
- [ ] Issue-tracker doc: `Rot vor Fix:` item in every feat/fix checklist
- [ ] `check:scratch` rule with PHPUnit cases (missing line fails, present passes, pre-0022 ignored)
- [ ] Manual test sections renamed to spec numbers; references updated
- [ ] Merge attribute and driver registration for the index, documented with the hooks setup
- [ ] Manual check recorded: merging two branches that both touch the index leaves no conflict markers
- [ ] `Rot vor Fix:` line recorded
- [ ] `bash scripts/test.sh` green

## Comments
