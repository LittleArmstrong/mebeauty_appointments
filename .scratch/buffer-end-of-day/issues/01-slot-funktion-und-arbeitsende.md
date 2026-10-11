# 01: Reine Slot-Funktion, Tagesende-Puffer nur am Arbeitsende

**What to build:** Für Dienstleister mit einem Teilnehmer pro Slot werden Slots direkt vor einem Termin, einer Pause, einer Unavailability oder einer Blocked Period wieder angeboten. Der Tagesende-Puffer gilt nur vor dem Arbeitsende des Tages (inklusive Arbeitsplan-Ausnahme). Die Slot-Erzeugung ist eine reine Funktion ohne Settings- und Datenbankzugriff und per Unit-Test prüfbar.

**Blocked by:** None (can start immediately)

**Status:** ready-for-agent

- [ ] Öffentliche reine Funktion: Datum, freie Spannen, Dauer, Intervall, Tagesende-Puffer und Arbeitsende → Slot-Startzeiten
- [ ] Der Aufrufer übergibt das Arbeitsende aus dem effektiven Tagesplan (Arbeitsplan oder Ausnahme)
- [ ] Unit-Tests (Dauer 60, Intervall 15, Puffer 15, Arbeitsende 18:00): Option an → letzter Slot 16:45; Option aus → 17:00; Spanne endet um 12:00 an einem Terminbeginn → letzter Slot 11:00; Spanne endet am Pausenbeginn → bündig; Ausnahme bis 20:00 → Puffer vor 20:00; zu kurze Spanne → keine Slots
- [ ] Der Puffer nach Terminen ist unverändert
- [ ] Manuelle Gegenprobe: Zwei Termine in beiden Buchungsreihenfolgen → identische freie Slots
- [ ] PHPUnit und `php -l` grün
