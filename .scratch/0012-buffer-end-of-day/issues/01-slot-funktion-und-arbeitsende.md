# 01: Reine Slot-Funktion; Tagesende-Puffer nur am effektiven Arbeitsende (Einzeltermine)

**What to build:** Bei Dienstleistern mit einem Teilnehmer pro Slot werden Slots, die bündig vor einer Pause, einem Termin, einer Unavailability oder einer Blocked Period enden, wieder angeboten. Bei aktivierter Option gilt `Terminende + Pufferzeit ≤ effektives Arbeitsende` nur für die Spanne, die am Arbeitsende endet. Die Slot-Erzeugung ist eine reine Funktion und per Unit-Test geprüft.

**Blocked by:** None (can start immediately)

**Status:** resolved

- [x] Öffentliche reine Funktion: Datum, freie Spannen, Dauer, Intervall, Pufferzeit, Option, effektives Arbeitsende → Slot-Startzeiten; ohne Settings- und Datenbankzugriff
- [x] Die Verfügbarkeitsberechnung übergibt das Ende des effektiven Tagesplans (Wochentagsplan oder Arbeitsplan-Ausnahme)
- [x] Unit-Tests (Dauer 60, Intervall 15, Puffer 15): Arbeitsende 18:00 Option an → letzter Slot 16:45; Option aus → 17:00; Ausnahme bis 14:00 → 12:45; Pause ab 12:00 → Slot 11:00; Termin ab 15:00 → Slot 14:00; Blocked Period ab 17:00 → Slot 16:00; Unavailability bis Arbeitsende → kein Puffer; Termin bis 10:00 → erster Slot 10:15; zu kurze Spanne → keine Slots
- [x] Puffer nach Terminen unverändert
- [x] `composer test` und `php -l` grün

## Comments

- `Availability::generate_hours_for_periods()` (rein, statisch) und `end_of_day_buffer_for_period()`; das Arbeitsende kommt aus `get_working_day_end()` (Ausnahme vor Wochentagsplan). Unit-Tests für alle Szenarien; Gegenprobe mit simuliertem Altverhalten (Puffer an jedem Periodenende) → 4 Tests rot.
- Live (Montag, Service 30 Min., Puffer 15, Pause 14:30–15:00, Arbeitsende 18:00): 14:00 wird angeboten (bündig vor der Pause), letzter Slot 17:15.
