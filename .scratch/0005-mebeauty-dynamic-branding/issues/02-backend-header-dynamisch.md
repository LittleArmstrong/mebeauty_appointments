# 02: Backend-Header dynamisch

**What to build:** Der Backend-Header zeigt den Firmennamen und ein hochgeladenes Firmenlogo dynamisch (generisches Logo als Fallback) und den 1.6.0-Untertitel („Online Appointment Scheduler") — statt MeBeauty-Fallback und MeBeauty-Slogan.

**Blocked by:** 01 Generische Defaults wiederherstellen

**Status:** ready-for-agent

- [ ] Firmenname kommt aus `company_name` mit generischem Fallback (nicht „MeBeauty")
- [ ] Logo kommt aus `company_logo` (Data-URI), Fallback auf das generische Standard-Logo (nicht `assets/img/mebeauty/logo-128.png`)
- [ ] Untertitel zeigt wieder „Online Appointment Scheduler" (1.6.0-Text)
- [ ] Der Header rendert ohne hartes MeBeauty-Branding, bleibt aber über Settings auf MeBeauty konfigurierbar
