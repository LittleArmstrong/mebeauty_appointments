# 04: Pufferzeit in der Verfügbarkeits-Berechnung

**What to build:** Mit `appointment_buffer_time` > 0 bieten Wizard und API keinen Slot an, der innerhalb der Pufferzeit nach einem bestehenden Termin beginnt (effektiv wird Service-Dauer + Puffer reserviert). Mit `appointment_buffer_end_of_day` = 1 endet der letzte buchbare Slot entsprechend früher vor Arbeitsende. Puffer 0 lässt das Verhalten unverändert. Multi-Attendant-Services verhalten sich analog. Unavailabilities und gesperrte Zeiträume werden durch den Puffer nicht verlängert.

**Blocked by:** 02

**Status:** ready-for-agent

- [ ] Termin 10:00–11:00, Puffer 15: Slots 11:00–11:15 werden nicht angeboten; ab 11:15 wieder buchbar
- [ ] Tagesende aktiv: Arbeitszeit 10–19, Dauer 60, Puffer 15 → letzter Slot 17:45
- [ ] Tagesende deaktiviert: gleiche Konfiguration → letzter Slot 18:00
- [ ] Puffer 0 → Verhalten unverändert (Regression ausgeschlossen)
- [ ] Puffer gilt nur nach echten Terminen, nicht nach Unavailabilities/Blocked Periods und nie vor Terminen
- [ ] Multi-Attendant-Services respektieren den Puffer gleichermaßen
- [ ] Serverseitige Validierung erzwingt dieselbe Regel
- [ ] `composer test` bleibt grün
