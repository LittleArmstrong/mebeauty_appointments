# 02: Backend-Header dynamisch

**What to build:** Der Backend-Header zeigt den Firmennamen und ein hochgeladenes Firmenlogo dynamisch (generisches Logo als Fallback) und den 1.6.0-Untertitel („Online Appointment Scheduler") — statt MeBeauty-Fallback und MeBeauty-Slogan.

**Blocked by:** 01 Generische Defaults wiederherstellen

**Status:** resolved

- [x] Firmenname kommt aus `company_name` mit generischem Fallback (nicht „MeBeauty")
- [x] Logo kommt aus `company_logo` (Data-URI), Fallback auf das generische Standard-Logo (nicht `assets/img/mebeauty/logo-128.png`)
- [x] Untertitel zeigt wieder „Online Appointment Scheduler" (1.6.0-Text)
- [x] Der Header rendert ohne hartes MeBeauty-Branding, bleibt aber über Settings auf MeBeauty konfigurierbar

## Comments

- Umgesetzt in a6dba558. Backend-Header: `company_name` mit `DEFAULT_COMPANY_NAME`, `company_logo` mit Fallback `assets/img/logo.png`, Untertitel „Online Appointment Scheduler“. Fallback-Name ist der Wert der Konstante (`MeBeauty`), nicht mehr hart im View.
