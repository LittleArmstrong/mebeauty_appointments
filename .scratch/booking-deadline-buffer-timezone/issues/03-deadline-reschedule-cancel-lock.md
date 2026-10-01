# 03: Frist-Modus: Sperre für Storno/Umbuchen

**What to build:** Öffnet eine Kundin ihren Termin-Manage-Link, nachdem die Frist abgelaufen ist (Modus `fixed_time`), sieht sie die Sperrseite mit der konkreten Frist als Datum + Uhrzeit im Firmen-Datumsformat; Umbuchen und Stornieren sind blockiert. Vor Fristablauf funktioniert beides. Im Modus `minutes` bleiben Sperr-Verhalten und -Message unverändert.

**Blocked by:** 02, 07

**Status:** ready-for-agent

- [ ] Manage-Link nach Fristablauf zeigt Sperrseite mit Datum + Uhrzeit der Frist
- [ ] Manage-Link vor Fristablauf erlaubt Umbuchen und Stornieren weiterhin
- [ ] Die Frist-Berechnung nutzt dieselbe Semantik wie die Verfügbarkeits-Berechnung (Tag D − N um HH:MM, Provider-Zeitzone)
- [ ] Modus `minutes`: bestehendes Sperr-Verhalten und bestehende Message unverändert
- [ ] `composer test` bleibt grün
