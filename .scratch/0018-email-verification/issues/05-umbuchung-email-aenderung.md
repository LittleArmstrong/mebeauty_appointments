# 05: Umbuchung mit E-Mail-Änderung per Link

**What to build:** Ändert eine Kundin bei aktiver Bestätigung während einer Umbuchung ihre E-Mail, gelten neue Zeit und übrige Angaben sofort (0017/03). An die neue Adresse geht ein Bestätigungslink. Erst nach dem Klick kommt die neue E-Mail in die Termin-Kontaktdaten und in den Kundendatensatz, und zwar nur, wenn kein anderer Kunde sie hat. Bis dahin gehen Mails an die alte, bestätigte Adresse. Eine Umbuchung ohne E-Mail-Änderung braucht keine Bestätigung.

**Blocked by:** 01, 0017/03

**Status:** ready-for-agent

- [ ] Umbuchung mit geänderter E-Mail legt eine Bestätigung mit Zweck „E-Mail-Änderung“ an; Umbuchungsmail an die alte Adresse
- [ ] Klick übernimmt die E-Mail in Termin-Kontaktdaten und Datensatz (Belegt-Regel aus 0017)
- [ ] Ablauf ändert nichts am Termin
- [ ] Umbuchung ohne E-Mail-Änderung unverändert ohne Bestätigung
- [ ] Smoke-Fall: Umbuchung mit neuer E-Mail → Zeit sofort geändert, E-Mail erst nach Link
- [ ] `bash scripts/test.sh` grün
