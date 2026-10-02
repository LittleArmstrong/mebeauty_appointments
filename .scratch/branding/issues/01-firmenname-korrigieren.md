# 01: Firmenname korrigieren

**What to build:** Der Firmenname-Settingswert steht auf „MeBeauty“ statt „Test“, sodass alle Stellen, die den Firmennamen anzeigen (bestehender Buchungsseiten-Titel sowie die in Ticket 03/04 dynamisch angebundenen Titel und der Backend-Header), „MeBeauty“ ausgeben.

**Blocked by:** None (can start immediately).

**Status:** ready-for-agent

- [ ] `setting('company_name')` liefert „MeBeauty“ (nicht „Test“)
- [ ] Der öffentliche Buchungsseiten-Titel enthält „MeBeauty“ statt „Test“
- [ ] Nur der Settings-Wert ist geändert — keine Schema-Migration
