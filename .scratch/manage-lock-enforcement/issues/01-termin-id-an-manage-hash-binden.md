# 01: Termin-ID nur mit gültigem Manage-Hash akzeptieren

**What to build:** Eine Buchungsanfrage kann einen bestehenden Termin nur noch verändern, wenn sie den Hash des Manage-Links mitbringt und dieser zur übermittelten Termin-ID gehört. Der Booking-Flow schickt im Manage-Modus den Hash mit. Ohne Manage-Modus wird eine mitgeschickte Termin-ID verworfen; es entsteht eine Neubuchung, der bestehende Termin bleibt unverändert. Ist der Manage-Modus gesetzt, aber der Hash fehlt oder passt nicht, wird die Anfrage ohne Seiteneffekte abgelehnt.

**Blocked by:** None (can start immediately)

**Status:** ready-for-agent

- [ ] Der Booking-Flow schickt beim Absenden im Manage-Modus den Termin-Hash mit
- [ ] POST ohne Manage-Modus, aber mit fremder Termin-ID → neuer Termin, der fremde Termin ist unverändert
- [ ] POST im Manage-Modus mit falschem oder fehlendem Hash → Fehlerantwort, nichts gespeichert (kein Kunde, keine Consents, kein Termin, keine Benachrichtigung)
- [ ] POST im Manage-Modus mit passendem Hash → Umbuchung funktioniert wie bisher
- [ ] Neues HTTP-Smoke-Skript deckt die drei Fälle ab und läuft lokal grün
- [ ] PHPUnit, `php -l`, `node --check` und Layout-Smoke-Test grün
