# 01: Frist- und Puffer-Einstellungen beim Speichern validieren

**What to build:** Speichert ein Admin die Buchungseinstellungen mit einem ungültigen Wert für Buchungsfrist, Manage-Frist oder Pufferzeit, wird nichts gespeichert, und das Backend zeigt eine Fehlermeldung mit dem betroffenen Feld. Gültige Werte (inklusive der Migrations-Defaults) werden wie bisher gespeichert.

**Blocked by:** None (can start immediately)

**Status:** ready-for-agent

- [ ] Reine Validierungsfunktion für die Fork-Settings: Uhrzeiten `HH:MM` 00:00–23:59; Tage und Minuten ganze Zahl ≥ 0; Modi nur aus den vorgesehenen Werten; „Puffer vor Arbeitsende“ nur 0/1
- [ ] Unit-Tests: Grenzwerte gültig (00:00, 23:59, 0); ungültig: 24:00, 9:5, -1, „abc“, unbekannter Modus
- [ ] Speichern der Buchungseinstellungen bricht bei ungültigem Wert vollständig ab (kein Teil-Speichern), und die Fehlermeldung nennt das Feld
- [ ] Upstream-Settings bleiben ungeprüft
- [ ] PHPUnit und `php -l` grün
