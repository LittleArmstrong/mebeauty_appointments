# 03: Service-Buchungsabfrage lesbarer, Cache-Prüfung im HTML-Helper einmalig

**What to build:** Die Abfrage der Service-Buchungen für die Slot-Kapazität ist eine zusammenhängende Kette, der Terminausschluss steht darin. Der HTML-Bereinigungs-Helper prüft sein Cache-Verzeichnis nur einmal pro Request. Das Verhalten ist unverändert.

**Blocked by:** None (can start immediately)

**Status:** resolved

- [x] Der Terminausschluss steht bedingt in der Abfragekette; das Ergebnis ist identisch
- [x] Das Ergebnis der Verzeichnisprüfung wird pro Request statisch gecacht
- [x] Die bestehenden Helper-Tests sind grün, ggf. um einen Test für wiederholte Aufrufe ergänzt
- [x] PHPUnit und `php -l` grün

## Comments

- Verzeichnisprüfung wird pro Cache-Pfad statisch gemerkt (nicht global), weil Tests `cache_path` innerhalb eines Prozesses wechseln. Neuer Test für wiederholte Aufrufe. Service-Buchungsabfrage als eine Kette; Smoke über einen Service mit Slot-Kapazität grün.
