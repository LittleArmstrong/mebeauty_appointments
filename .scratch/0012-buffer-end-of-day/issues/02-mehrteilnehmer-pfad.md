# 02: Mehrteilnehmer-Services mit derselben Pufferregel

**What to build:** Services mit mehreren Teilnehmern pro Slot verhalten sich beim Tagesende-Puffer wie Einzeltermine: Er gilt nur für die Spanne, die am effektiven Arbeitsende des Dienstleisters endet, nicht vor Pausen, Terminen, Unavailabilities oder Blocked Periods. Das Vorziehen des Konfliktfensters um den Puffer nach Terminen bleibt unverändert.

**Blocked by:** 01 (Reine Slot-Funktion; Tagesende-Puffer nur am effektiven Arbeitsende)

**Status:** ready-for-agent

- [ ] Die Schleifenbedingung im Mehrteilnehmer-Pfad berücksichtigt den Tagesende-Puffer nur für die Spanne, deren Ende das effektive Arbeitsende ist (möglichst über dieselbe Prüffunktion wie in 01)
- [ ] Manuelle Prüfung mit einem Service mit mehreren Teilnehmern: Ein Slot bündig vor der Pause wird angeboten; am Arbeitsende gilt die Option (an und aus); bei einer Arbeitsplan-Ausnahme gilt deren Ende
- [ ] Szenario und Ergebnis der manuellen Prüfung unter `## Comments` dokumentiert
- [ ] `composer test` und `php -l` grün
