# 01: German style guide and key parity test

**What to build:** A short German style guide in `docs/` (tone "Sie", glossary terms, function over word-for-word,
preferred verbs, no beauty vocabulary in the core, when to add an own key instead of rewording a shared one),
linked from `docs/manage-translations.md`. A PHPUnit test that German and English `translations_lang.php` define
the same keys.

**Blocked by:** –

**Status:** in-progress

- [x] Style guide in `docs/` with term table matching `GLOSSARY.md`
- [x] Linked from `docs/manage-translations.md`
- [x] PHPUnit key parity test German ↔ English
- [x] `Rot vor Fix:` line recorded
- [ ] `bash scripts/test.sh` green

## Comments

- Implementer (subagent, worktree `agent-a3d9ad36f7bb02e1e`, branch `agent/0025-01-style-guide`): style guide
  `docs/translation-style-de.md` (Sie, function over word-for-word, industry-neutral core, term table per
  `GLOSSARY.md`, verbs buchen/umbuchen/stornieren, own fork key in German and English instead of rewording a shared
  one, English only where functionally wrong), linked from `docs/manage-translations.md`. PHPUnit test
  `tests/Unit/TranslationKeyParityTest.php` (both directions, failure message lists the missing keys). German and
  English currently define the same 659 keys; no translation changed.
- Rot vor Fix: with `site_whatsapp_chat` temporarily removed from `german/translations_lang.php` the test failed
  with "Keys missing in german/translations_lang.php: site_whatsapp_chat" (2 tests, 1 failure); restored → OK.
- Validation: `bash scripts/test.sh` aborted at `docker compose up -d` because port 1025 is held by a foreign
  container (`platform-mailpit-1`, other project), so the box above stays open. Run individually in the worktree:
  phpunit OK (233 tests), check:format, check:scratch, check:views, check:assets OK. Migrations untouched; smokes not
  run (text/test-only change).
