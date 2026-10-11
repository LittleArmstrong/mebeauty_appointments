# 05: Regeln für Tests, Belege und Sitzungen festschreiben

**What to build:** Die Regeln aus der Retro stehen an genau einer Stelle: „Tests must go red“ und die `html_vars()`-Reviewer-Regel in `CODING_STANDARDS.md`, die Belegpflicht für Verhaltensaussagen in `docs/agents/issue-tracker.md`, der CI-Check nach dem Push in `docs/agents/validation-and-completion.md`. Sitzungsexporte entfallen.

**Blocked by:** 04

**Status:** ready-for-agent

- [ ] `CODING_STANDARDS.md`: neue Tests in Tickets mit Verhaltensänderung sind gegen das alte Verhalten rot, Beleg `Rot vor Fix: …` im Ticket-Kommentar, Testdaten stellen den behaupteten Grenzfall her
- [ ] `CODING_STANDARDS.md`: View-Ausgaben über `e()`, auch für Werte aus `html_vars()`/`vars()`; Verweis auf `check:views`
- [ ] `docs/agents/issue-tracker.md`: Verhaltensaussagen im Problem-Abschnitt einer Spec tragen `pfad:zeile`
- [ ] `docs/agents/validation-and-completion.md`: nach einem Push `gh run list --branch <branch>`
- [ ] `docs/agents/session-traceability.md` und `docs/agent-sessions/` gelöscht; `AGENTS.md` nennt stattdessen: Sitzungen und Handoffs sind Wegwerfware, alles Wichtige steht in Spec, Ticket, ADR oder `docs/`
