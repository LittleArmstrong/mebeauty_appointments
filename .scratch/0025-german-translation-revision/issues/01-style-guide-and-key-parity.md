# 01: German style guide and key parity test

**What to build:** A short German style guide in `docs/` (tone "Sie", glossary terms, function over word-for-word,
preferred verbs, no beauty vocabulary in the core, when to add an own key instead of rewording a shared one),
linked from `docs/manage-translations.md`. A PHPUnit test that German and English `translations_lang.php` define
the same keys.

**Blocked by:** –

**Status:** in-progress

- [ ] Style guide in `docs/` with term table matching `GLOSSARY.md`
- [ ] Linked from `docs/manage-translations.md`
- [ ] PHPUnit key parity test German ↔ English
- [ ] `Rot vor Fix:` line recorded
- [ ] `bash scripts/test.sh` green
