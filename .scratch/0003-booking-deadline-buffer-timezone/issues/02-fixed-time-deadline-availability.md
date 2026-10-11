# 02: Frist-Modus in der Verfügbarkeits-Berechnung

**What to build:** Bei `booking_deadline_mode` = `fixed_time` bieten Buchungs-Wizard, Datumswähler-Ausgrauung und Verfügbarkeits-API für einen Tag D keine Zeiten mehr an, sobald „jetzt“ nach (Tag D − `booking_deadline_days`) um `booking_deadline_time` liegt — der gesamte Tag D ist gesperrt (auch späte Slots). Vergangene Stunden bleiben gefiltert. Im Festzeit-Modus gilt keine Mindestvorlaufzeit. Der Modus `minutes` behält das bestehende Verhalten exakt bei. Die serverseitige Buchungsvalidierung erzwingt dieselbe Regel.

**Blocked by:** 01

**Status:** resolved

- [ ] `fixed_time` mit `18:00` und `days=1`: Nach heute 18:00 liefert morgen keine Stunden mehr und ist im Datumswähler ausgegraut; übermorgen bleibt buchbar
- [ ] `fixed_time` mit `08:00` und `days=0`: gleichtägige Slots sind nur bis 08:00 am selben Tag buchbar
- [ ] `minutes`-Modus verhält sich unverändert (Regression ausgeschlossen)
- [ ] Serverseitige Validierung lehnt einen Slot ab, der nach Fristablauf übergeben wird (Parallele-Buchungs-Pfad)
- [ ] „Beliebiger Anbieter“-Fluss respektiert die Frist
- [ ] Bewertung erfolgt in der Provider-Zeitzone
- [ ] `composer test` bleibt grün
