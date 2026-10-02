# 04: Backend-Header dynamisch

**What to build:** Der Backend-Header zeigt den Firmennamen, ein hochladbares Firmen-Logo (Fallback auf das Standard-App-Logo) und den Untertitel „Kosmetik & Beauty Studio“ — statt der hart kodierten Werte „EASY!APPOINTMENTS“ / „Online Appointment Scheduler“.

**Blocked by:** 01 Firmenname korrigieren, 02 Bilder: Favicon + App-Logo auf MeBeauty

**Status:** resolved

- [ ] Firmenname kommt aus `company_name` (Fallback „MeBeauty“)
- [ ] Logo kommt aus `company_logo` (Data-URI), Fallback auf das Standard-App-Logo
- [ ] Untertitel „Kosmetik & Beauty Studio“
- [ ] Der Header rendert ohne das alte Easy!Appointments-Branding
