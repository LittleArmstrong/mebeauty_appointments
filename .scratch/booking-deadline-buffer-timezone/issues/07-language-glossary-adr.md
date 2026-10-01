# 07: Sprache, Glossar & ADR

**What to build:** Alle neuen Sprachschlüssel existieren in Deutsch und Englisch (Labels + Hinweise für Frist-Modus, Frist-Uhrzeit, Tagesabstand, Pufferzeit, Puffer am Tagesende, Timezone-Anzeige und -Quelle sowie die Sperr-Message-Variante mit Datum + Uhrzeit der Frist). Das deutsche Label des bestehenden Minuten-Settings wird zu „Mindestvorlaufzeit“ korrigiert. Das Glossar erhält die vier kanonischen Begriffe Vorlaufzeit, Buchungsfrist, Tagesabstand und Pufferzeit. ADR 0002 dokumentiert Frist-Modi und Puffer-Semantik mit Trade-offs.

**Blocked by:** 01

**Status:** ready-for-agent

- [ ] Alle neuen Keys sind in `german` und `english` vorhanden; andere Sprachen bleiben unberührt (fallen auf Englisch zurück)
- [ ] Sperr-Message-Variante enthält Platzhalter für Datum + Uhrzeit der Frist
- [ ] Deutsches Label des bestehenden Minuten-Settings lautet „Mindestvorlaufzeit“ (Hinweistext angepasst, keine Funktionsänderung)
- [ ] Glossar enthält die Begriffe Vorlaufzeit, Buchungsfrist, Tagesabstand, Pufferzeit — ohne Implementierungsdetails, im bestehenden Format
- [ ] ADR 0002 enthält Kontext, Entscheidungen (Entweder/Oder-Frist ohne Mindestvorlaufzeit; Puffer global, nur nach Terminen, Tagesende optional) und Konsequenzen; Format wie die bestehende ADR
