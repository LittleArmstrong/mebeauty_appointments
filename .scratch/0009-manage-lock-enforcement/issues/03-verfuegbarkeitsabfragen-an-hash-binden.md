# 03: Verfügbarkeitsabfragen schließen nur den eigenen Termin aus

**What to build:** Die öffentlichen Abfragen für freie Zeiten und gesperrte Tage schließen im Manage-Modus nur noch den Termin aus, der zum mitgeschickten Manage-Hash gehört. Eine übermittelte Termin-ID wird ignoriert. Ohne gültigen Hash wird ohne Ausschluss gerechnet. Kunden mit Manage-Link sehen ihren alten Zeitpunkt weiterhin als frei, fremde Termine erscheinen nie als frei.

**Blocked by:** 01 (Umbuchung nur mit gültigem Manage-Hash)

**Status:** resolved

- [x] Der Booking-Flow schickt bei beiden Abfragen im Manage-Modus den Hash statt der Termin-ID
- [x] Der Server löst den Hash wie in 01 auf und verwendet nur die so ermittelte ID als Ausschluss
- [x] Abfrage mit fremder Termin-ID ohne Hash → der fremde Termin bleibt belegt (Smoke-Fall)
- [x] Abfrage mit gültigem Hash → der eigene alte Zeitpunkt erscheint als frei
- [x] Die Abfragen bleiben POST (bewusst, siehe Spec)
- [x] `composer test`, `php -l`, `node --check`, Layout-Smoke-Test grün

## Comments

- `find_managed_appointment_id()` löst den Hash auf; `appointment_id` wird von beiden Abfragen nicht mehr gelesen. Smoke-Fälle 8/9; Gegenprobe gegen den vorherigen Controller: beide rot.

- Review (Opus, Sicherheitsfokus): kein verbleibender ID-Umgehungsweg. Spec korrigiert: `get_unavailable_dates` ist GET (Hash in der Query, wie beim Manage-Link selbst); Hash-Stärke ≈ 2⁶² wegen Vergleich ohne Groß-/Kleinschreibung; Restrisiko „Zuordnung per E-Mail überschreibt fremde Stammdaten“ (Upstream) dokumentiert. Kein eigener Smoke-Fall für gesperrte Tage: Der Ausschluss eines einzelnen Termins ändert die Tagesliste praktisch nie, ein solcher Test würde den alten Code nicht unterscheiden.
