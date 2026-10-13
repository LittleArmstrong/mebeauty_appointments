# 02: Ablauf, Wiederherstellung und Aufräumen

**What to build:** Eine Vormerkung, die nach 10 Minuten noch nicht bestätigt ist, gibt ihren Slot frei, und ein Cronjob ist dafür nicht nötig. Klickt die Kundin den Link später (bis 24 Stunden), wird gebucht, sofern der Slot noch frei ist. Ist er vergeben, erscheint eine Seite „Leider abgelaufen“ mit Link zur Buchung. Nach 24 Stunden ist der Link ungültig, und die Wiederherstellungsdaten sind gelöscht.

**Blocked by:** 01

**Status:** ready-for-agent

- [ ] Regel-Modul: Zustand einer Vormerkung zu einem Zeitpunkt (gültig / abgelaufen aber wiederherstellbar / verfallen); Unit-Tests an den Grenzen (10 Minuten, 24 Stunden)
- [ ] Lazy Cleanup vor Verfügbarkeitsabfragen, beim Absenden und beim Laden des Backend-Kalenders löscht abgelaufene Vormerkungs-Termine und verfallene Wiederherstellungsdaten
- [ ] `console cleanup` macht dasselbe
- [ ] Später Klick: Slot frei → Termin neu angelegt und bestätigt (vorhandene Verfügbarkeitsprüfung); Slot belegt → Seite „abgelaufen“
- [ ] Zweck „E-Mail-Änderung“ löscht beim Ablauf keinen Termin
- [ ] Smoke mit zurückdatierter Vormerkung (Fixture): Slot frei nach Ablauf; später Klick bei freiem bzw. belegtem Slot
- [ ] `bash scripts/test.sh` grün
