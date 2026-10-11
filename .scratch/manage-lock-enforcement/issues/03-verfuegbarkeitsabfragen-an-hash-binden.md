# 03: Verfügbarkeitsabfragen schließen nur den eigenen Termin aus

**What to build:** Die öffentlichen Abfragen für freie Zeiten und gesperrte Tage schließen im Manage-Modus nur noch den Termin aus, der zum mitgeschickten Manage-Hash gehört. Eine übermittelte Termin-ID wird ignoriert. Ohne gültigen Hash wird ohne Ausschluss gerechnet. Kunden mit Manage-Link sehen ihren alten Zeitpunkt weiterhin als frei, fremde Termine erscheinen nie als frei.

**Blocked by:** 01 (Umbuchung nur mit gültigem Manage-Hash)

**Status:** ready-for-agent

- [ ] Der Booking-Flow schickt bei beiden Abfragen im Manage-Modus den Hash statt der Termin-ID
- [ ] Der Server löst den Hash wie in 01 auf und verwendet nur die so ermittelte ID als Ausschluss
- [ ] Abfrage mit fremder Termin-ID ohne Hash → der fremde Termin bleibt belegt (Smoke-Fall)
- [ ] Abfrage mit gültigem Hash → der eigene alte Zeitpunkt erscheint als frei
- [ ] Die Abfragen bleiben POST (bewusst, siehe Spec)
- [ ] `composer test`, `php -l`, `node --check`, Layout-Smoke-Test grün
