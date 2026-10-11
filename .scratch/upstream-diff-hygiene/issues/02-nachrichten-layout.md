# 02: Nachrichten-Layout bereinigen und Trennstriche korrigieren

**What to build:** Das Nachrichten-Layout hat wieder die Upstream-Formatierung. Die Fußzeile zeigt Trennstriche nur zwischen vorhandenen Links: kein führender „|“, bei einem Link keiner, bei zwei Links genau einer.

**Blocked by:** None (can start immediately)

**Status:** ready-for-agent

- [ ] Der Diff des Nachrichten-Layouts gegen die Basis enthält nur inhaltliche Änderungen
- [ ] 0 Links → kein Trennstrich; 1 Link → kein Trennstrich; 2 Links → genau einer dazwischen
- [ ] Manuelle Probe auf einer Nachrichtenseite (z. B. Buchungsbestätigung)
- [ ] Layout-Smoke-Test, PHPUnit und `php -l` grün
