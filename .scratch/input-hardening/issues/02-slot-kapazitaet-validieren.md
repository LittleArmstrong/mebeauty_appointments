# 02: Slot-Kapazität eines Services validieren

**What to build:** Ein Service mit einer Slot-Kapazität, die keine ganze Zahl ≥ 0 ist (z. B. „abc“ oder „-1“), wird beim Speichern abgelehnt, im Backend wie über die API, mit derselben Art Validierungsfehler wie andere ungültige Service-Felder. Leer oder nicht gesetzt bleibt erlaubt und bedeutet unbegrenzt.

**Blocked by:** None (can start immediately)

**Status:** ready-for-agent

- [ ] „abc“, „-1“ und „1.5“ werden abgelehnt
- [ ] „0“, „3“ und leer/nicht gesetzt werden akzeptiert
- [ ] Die API liefert für einen ungültigen Wert eine Validierungsfehler-Antwort
- [ ] Unit-Test für die Prüfregel
- [ ] PHPUnit und `php -l` grün
