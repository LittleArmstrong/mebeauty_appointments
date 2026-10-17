# 05: Review rules, spec convention for server responses, measurement option form

**What to build:** `CODING_STANDARDS.md` gets two rules: global scripts (`assets/js/app.js`,
`assets/js/utils/message.js`) use only what the installation page also loads, with dynamic text escaped by the
server; a script that creates test data stores the raw setup output before anything else can fail and cleans up from
it. `docs/agents/development-conventions.md` asks spec writers to state status code and fields when a spec changes a
server response. The booking measurement accepts `--name value` as well as `--name=value`.

**Blocked by:** None (can start immediately)

**Status:** ready-for-agent

- [ ] Two rules in `CODING_STANDARDS.md`
- [ ] Spec-writing note in `docs/agents/development-conventions.md`
- [ ] Measurement accepts both option forms; header comment updated
- [ ] Manual check recorded: `--viewport 390x844` and `--viewport=390x844` give the same table
- [ ] `Rot vor Fix:` line recorded (`entfällt – <reason>` is fine for docs and tooling)
- [ ] `bash scripts/test.sh` green

## Comments
