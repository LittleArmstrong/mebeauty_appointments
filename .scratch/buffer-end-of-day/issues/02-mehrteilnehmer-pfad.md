# 02: Mehrteilnehmer-Services mit derselben Pufferregel

**What to build:** Services mit mehreren Teilnehmern pro Slot verhalten sich beim Tagesende-Puffer wie Einzeltermine: Der Puffer gilt nur vor dem Arbeitsende des Dienstleisters, nicht vor Terminen, Pausen oder Unavailabilities. Die Verschiebung des Konfliktfensters um den Puffer nach Terminen bleibt unverändert.

**Blocked by:** 01 (Reine Slot-Funktion, Tagesende-Puffer nur am Arbeitsende)

**Status:** ready-for-agent

- [ ] Ein Mehrteilnehmer-Service bietet einen Slot an, der bündig vor einer Pause endet
- [ ] Am Arbeitsende gilt der Puffer gemäß Option
- [ ] Das Arbeitsende wird aus dem effektiven Tagesplan inkl. Ausnahme bestimmt
- [ ] Manuelle Prüfung dokumentiert (Szenario und Ergebnis im Ticket unter `## Comments`)
- [ ] PHPUnit und `php -l` grün
