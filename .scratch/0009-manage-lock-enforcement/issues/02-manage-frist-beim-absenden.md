# 02: Manage-Frist beim Absenden gegen den Original-Termin prüfen

**What to build:** Bei einer gültigen Umbuchung (siehe 01) prüft der Server die Umbuchungs-/Stornierungsfrist erneut. Maßgeblich sind der gespeicherte Beginn des Original-Termins und die Zeitzone des ursprünglichen Dienstleisters, genau wie beim Öffnen des Manage-Links. Ist die Frist abgelaufen, wird die Umbuchung mit derselben Sperrmeldung abgelehnt, und der Kunde sieht sie im Booking-Flow. Der neue Zeitpunkt unterliegt weiterhin der Buchungsfrist.

**Blocked by:** 01 (Umbuchung nur mit gültigem Manage-Hash)

**Status:** resolved

- [x] Die Fristprüfung nutzt dieselbe Sperrlogik (Manage-Variante) wie beim Öffnen des Manage-Links, mit dem gespeicherten Beginn und der Zeitzone des ursprünglichen Dienstleisters
- [x] Gesperrt → Fehlerantwort mit Sperrmeldung inkl. Grenzwert; nichts gespeichert, keine Benachrichtigung, kein Sync, kein Webhook
- [x] Der Booking-Flow zeigt die Fehlermeldung an (Fehlerbehandlung des Absende-Aufrufs geprüft und bei Bedarf ergänzt)
- [x] Ein Zielzeitpunkt nach Ablauf der Buchungsfrist wird weiterhin abgelehnt
- [x] Das Smoke-Skript aus 01 ist erweitert: Frist abgelaufen → abgelehnt; innerhalb der Frist → OK; Zielzeit nach Buchungsfrist → abgelehnt
- [x] Die Stornierung bleibt unverändert
- [x] `composer test`, `php -l`, Layout-Smoke-Test grün

## Comments

- `check_manage_lock()` prüft mit dem gespeicherten Beginn und der Zeitzone des ursprünglichen Dienstleisters (gleiche Sperrlogik wie beim Öffnen). Fehler erscheinen über den globalen Ajax-Fehlerdialog (`app.js`, `onAjaxError`). Smoke-Fälle 5 (Manage-Frist abgelaufen, Meldung geprüft), 6 (Zielslot nach Buchungsfrist) und 7 (gültige Umbuchung).
