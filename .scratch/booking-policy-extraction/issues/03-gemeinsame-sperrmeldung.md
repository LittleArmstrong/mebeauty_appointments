# 03: Gemeinsamer Baustein für die Sperrmeldung

**What to build:** Buchungsseite und Stornierungsseite erzeugen die Meldung „Termin gesperrt“ über denselben Baustein, mit identischem Text, Grenzwert und Seitendaten wie heute.

**Blocked by:** 01 (Booking-Policy für Fristen und Sperren mit Unit-Tests)

**Status:** ready-for-agent

- [ ] Ein gemeinsamer Helper/Trait baut die Seitendaten der Sperrmeldung aus der Sperre der Policy auf
- [ ] Die nahezu identischen Blöcke in Buchungs- und Stornierungs-Controller sind entfernt
- [ ] Manuelle Probe: Ein gesperrter Manage-Link und eine gesperrte Stornierung zeigen dieselbe Meldung wie vorher
- [ ] PHPUnit, `php -l` und Layout-Smoke-Test grün
