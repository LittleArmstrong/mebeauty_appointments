# 02: Manage-Frist beim Absenden gegen den Original-Termin prüfen

**What to build:** Bei einer gültigen Umbuchung (siehe 01) prüft der Server die Umbuchungs-/Stornierungsfrist erneut. Maßgeblich sind der gespeicherte Beginn des Original-Termins und die Zeitzone des ursprünglichen Dienstleisters, genau wie beim Öffnen des Manage-Links. Ist die Frist abgelaufen, wird die Umbuchung mit derselben Sperrmeldung abgelehnt, und der Kunde sieht sie im Booking-Flow. Der neue Zeitpunkt unterliegt weiterhin der Buchungsfrist.

**Blocked by:** 01 (Umbuchung nur mit gültigem Manage-Hash)

**Status:** ready-for-agent

- [ ] Die Fristprüfung nutzt dieselbe Sperrlogik (Manage-Variante) wie beim Öffnen des Manage-Links, mit dem gespeicherten Beginn und der Zeitzone des ursprünglichen Dienstleisters
- [ ] Gesperrt → Fehlerantwort mit Sperrmeldung inkl. Grenzwert; nichts gespeichert, keine Benachrichtigung, kein Sync, kein Webhook
- [ ] Der Booking-Flow zeigt die Fehlermeldung an (Fehlerbehandlung des Absende-Aufrufs geprüft und bei Bedarf ergänzt)
- [ ] Ein Zielzeitpunkt nach Ablauf der Buchungsfrist wird weiterhin abgelehnt
- [ ] Das Smoke-Skript aus 01 ist erweitert: Frist abgelaufen → abgelehnt; innerhalb der Frist → OK; Zielzeit nach Buchungsfrist → abgelehnt
- [ ] Die Stornierung bleibt unverändert
- [ ] `composer test`, `php -l`, Layout-Smoke-Test grün
