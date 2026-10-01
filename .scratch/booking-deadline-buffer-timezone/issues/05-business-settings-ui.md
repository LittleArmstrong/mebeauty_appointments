# 05: Business-Settings-UI für Frist, Puffer & Timezone

**What to build:** In den Business Settings lassen sich alle sieben Settings konfigurieren: ein Frist-Modus-Select (`minutes` / `fixed_time`) mit bedingten Feldern (Frist-Uhrzeit und Tagesabstand erscheinen nur im Festzeit-Modus), ein Puffer-Minuten-Input mit Tagesende-Checkbox sowie Selects für Timezone-Anzeige (`dropdown` / `confirm_only` / `hidden`) und Timezone-Quelle (`company` / `browser`). Speichern und Laden laufen über die bestehende generische Serialisierung.

**Blocked by:** 01, 07

**Status:** ready-for-agent

- [ ] Modus-Select: bei `fixed_time` werden Frist-Uhrzeit und Tagesabstand eingeblendet, bei `minutes` ausgeblendet
- [ ] Puffer-Input erlaubt `min="0"`; Tagesende-Checkbox steuert `appointment_buffer_end_of_day`
- [ ] Alle Felder werden gespeichert und nach Seiten-Neuladen korrekt angezeigt
- [ ] Die generische `[data-field]`-Serialisierung funktioniert unverändert (keine Doppel-Einträge)
- [ ] `npm run build` läuft fehlerfrei
