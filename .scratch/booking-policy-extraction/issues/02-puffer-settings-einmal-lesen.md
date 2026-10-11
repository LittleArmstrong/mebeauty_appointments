# 02: Puffer-Settings einmal pro Berechnung lesen

**What to build:** Die Verfügbarkeitsberechnung liest Pufferzeit und „Puffer vor Arbeitsende“ einmal je Instanz statt pro Zeitspanne, Tag und Dienstleister. Die Monatsansicht der Buchungsseite erzeugt dadurch deutlich weniger identische Datenbankabfragen. Die Slots bleiben identisch.

**Blocked by:** buffer-end-of-day/02

**Status:** ready-for-agent

- [ ] Die Puffer-Settings werden pro Instanz einmal gelesen und gecacht
- [ ] Die Slots für einen Beispieltag sind vor und nach der Änderung identisch (manuell oder per Unit-Test der Slot-Funktion)
- [ ] PHPUnit, `php -l` und Layout-Smoke-Test grün
