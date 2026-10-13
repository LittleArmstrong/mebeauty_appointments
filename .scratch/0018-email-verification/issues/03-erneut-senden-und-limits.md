# 03: Erneut senden und Limits

**What to build:** Im Wizard-Hinweis kann die Kundin die Bestätigungsmail erneut anfordern. Das geht frühestens 60 Sekunden nach der letzten Mail und höchstens 5-mal pro E-Mail-Adresse und Stunde, damit niemand fremde Postfächer zuspammt. Ein neuer Link macht den vorherigen ungültig. Die Meldungen verraten nicht, ob eine Adresse bekannt ist.

**Blocked by:** 01

**Status:** ready-for-agent

- [ ] Regel-Modul: Entscheidung „darf senden“ aus letzter Mail, Zähler und Zeitpunkt; Unit-Tests (Sperrfrist, Stundenlimit, Zurücksetzen nach einer Stunde)
- [ ] Endpunkt „erneut senden“ für eine bestehende Vormerkung; neuer Token ersetzt den alten
- [ ] Wizard-Button mit Rückmeldung (gesendet / bitte warten), Texte Deutsch/Englisch
- [ ] Das Stundenlimit gilt auch für neue Vormerkungen mit derselben Adresse
- [ ] `bash scripts/test.sh` grün
