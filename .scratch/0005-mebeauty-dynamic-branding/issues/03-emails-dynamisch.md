# 03: E-Mails dynamisch (Logo + Titel)

**What to build:** Benachrichtigungs-E-Mails verwenden das hochgeladene Firmenlogo (generisches Logo als Fallback) und den Firmennamen dynamisch. Das hochgeladene `company_logo` (Base64-Data-URI) wird per `addStringEmbeddedImage` direkt als `cid:logo.png` eingebettet — ohne Temp-Datei und ohne Umstellung des Speicherformats.

**Blocked by:** 01 Generische Defaults wiederherstellen

**Status:** ready-for-agent

- [ ] Ist `company_logo` gesetzt (Data-URI), wird es als `cid:logo.png` in E-Mails eingebettet (MIME aus der Data-URI abgeleitet)
- [ ] Ist kein `company_logo` gesetzt, wird das generische Standard-Logo eingebettet (nicht `assets/img/mebeauty/logo-192.png`)
- [ ] E-Mail-Titel/Betreffzeilen verwenden den Firmennamen mit generischem Fallback (nicht „MeBeauty")
- [ ] Die E-Mail-Templates referenzieren weiterhin `cid:logo.png` (keine Template-Änderung nötig)
