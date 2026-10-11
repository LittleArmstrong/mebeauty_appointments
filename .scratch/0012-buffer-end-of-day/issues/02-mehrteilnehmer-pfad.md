# 02: Mehrteilnehmer-Services mit derselben Pufferregel

**What to build:** Services mit mehreren Teilnehmern pro Slot verhalten sich beim Tagesende-Puffer wie Einzeltermine: Er gilt nur für die Spanne, die am effektiven Arbeitsende des Dienstleisters endet, nicht vor Pausen, Terminen, Unavailabilities oder Blocked Periods. Das Vorziehen des Konfliktfensters um den Puffer nach Terminen bleibt unverändert.

**Blocked by:** 01 (Reine Slot-Funktion; Tagesende-Puffer nur am effektiven Arbeitsende)

**Status:** resolved

- [x] Die Schleifenbedingung im Mehrteilnehmer-Pfad berücksichtigt den Tagesende-Puffer nur für die Spanne, deren Ende das effektive Arbeitsende ist (möglichst über dieselbe Prüffunktion wie in 01)
- [x] Manuelle Prüfung mit einem Service mit mehreren Teilnehmern: Ein Slot bündig vor der Pause wird angeboten; am Arbeitsende gilt die Option (an und aus); bei einer Arbeitsplan-Ausnahme gilt deren Ende
- [x] Szenario und Ergebnis der manuellen Prüfung unter `## Comments` dokumentiert
- [x] `composer test` und `php -l` grün

## Comments

- Schleifenbedingung nutzt `end_of_day_buffer_for_period()` mit dem Ende des effektiven Tagesplans (`$date_working_plan`, Ausnahme bereits berücksichtigt).
- Manuelle Prüfung 2026-10-11 (Service vorübergehend mit 2 Teilnehmern, Puffer 15, Montag, Pause 14:30–15:00, Arbeitsende 18:00): Option an → 14:00 angeboten, letzter Slot 17:15; Option aus → 14:00 angeboten, letzter Slot 17:30. Werte danach zurückgesetzt. Arbeitsplan-Ausnahme nicht live geprüft (gleiche Datenquelle wie der Einzelpfad, dort per Unit-Test abgedeckt).
