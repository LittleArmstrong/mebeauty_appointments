# 03: Firmenname und Firmenfarbe escaped ausgeben

**What to build:** Ein Firmenname mit Sonderzeichen (z. B. `Beauty & "Co"`) erscheint in Seitentitel, Social-Vorschau, Nachrichtenseiten und E-Mails korrekt, ohne kaputtes HTML. Eine ungültige Firmenfarbe zerschießt die E-Mails nicht, stattdessen gilt die Standardfarbe.

**Blocked by:** upstream-diff-hygiene/02 (Nachrichten-Layout zuerst auf Upstream-Formatierung zurücksetzen)

**Status:** ready-for-agent

- [ ] Alle vom Fork eingeführten oder geänderten Ausgaben des Firmennamens nutzen den Escape-Helper (Booking-Layout inkl. Social-Vorschau-Tags, Nachrichten-Layout, E-Mail-Templates)
- [ ] E-Mail-Templates verwenden die Firmenfarbe nur, wenn sie `#RGB`/`#RRGGBB` ist, sonst die Standard-Primärfarbe
- [ ] Manuelle Probe mit `Beauty & "Co"`: Der Quelltext enthält die Zeichen escaped, die Darstellung ist korrekt
- [ ] Layout-Smoke-Test, E-Mail-Logo-Smoke-Test, PHPUnit und `php -l` grün
