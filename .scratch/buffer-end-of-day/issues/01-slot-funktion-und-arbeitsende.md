# 01: Reine Slot-Funktion; Tagesende-Puffer nur am effektiven Arbeitsende (Einzeltermine)

**What to build:** Bei Dienstleistern mit einem Teilnehmer pro Slot werden Slots, die bündig vor einer Pause, einem Termin, einer Unavailability oder einer Blocked Period enden, wieder angeboten. Bei aktivierter Option gilt `Terminende + Pufferzeit ≤ effektives Arbeitsende` nur für die Spanne, die am Arbeitsende endet. Die Slot-Erzeugung ist eine reine Funktion und per Unit-Test geprüft.

**Blocked by:** None (can start immediately)

**Status:** ready-for-agent

- [ ] Öffentliche reine Funktion: Datum, freie Spannen, Dauer, Intervall, Pufferzeit, Option, effektives Arbeitsende → Slot-Startzeiten; ohne Settings- und Datenbankzugriff
- [ ] Die Verfügbarkeitsberechnung übergibt das Ende des effektiven Tagesplans (Wochentagsplan oder Arbeitsplan-Ausnahme)
- [ ] Unit-Tests (Dauer 60, Intervall 15, Puffer 15): Arbeitsende 18:00 Option an → letzter Slot 16:45; Option aus → 17:00; Ausnahme bis 14:00 → 12:45; Pause ab 12:00 → Slot 11:00; Termin ab 15:00 → Slot 14:00; Blocked Period ab 17:00 → Slot 16:00; Unavailability bis Arbeitsende → kein Puffer; Termin bis 10:00 → erster Slot 10:15; zu kurze Spanne → keine Slots
- [ ] Puffer nach Terminen unverändert
- [ ] `composer test` und `php -l` grün
