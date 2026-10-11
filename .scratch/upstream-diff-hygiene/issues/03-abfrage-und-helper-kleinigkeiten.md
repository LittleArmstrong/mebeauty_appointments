# 03: Service-Buchungsabfrage lesbarer, Cache-Prüfung im HTML-Helper einmalig

**What to build:** Die Abfrage der Service-Buchungen für die Slot-Kapazität ist eine zusammenhängende Kette, der Terminausschluss steht darin. Der HTML-Bereinigungs-Helper prüft sein Cache-Verzeichnis nur einmal pro Request. Das Verhalten ist unverändert.

**Blocked by:** None (can start immediately)

**Status:** ready-for-agent

- [ ] Der Terminausschluss steht bedingt in der Abfragekette; das Ergebnis ist identisch
- [ ] Das Ergebnis der Verzeichnisprüfung wird pro Request statisch gecacht
- [ ] Die bestehenden Helper-Tests sind grün, ggf. um einen Test für wiederholte Aufrufe ergänzt
- [ ] PHPUnit und `php -l` grün
