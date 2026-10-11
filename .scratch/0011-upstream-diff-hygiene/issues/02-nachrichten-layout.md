# 02: Nachrichten-Layout auf Upstream-Formatierung zurücksetzen und Trennstriche korrigieren

**What to build:** Das Nachrichten-Layout weicht nur noch inhaltlich von der Upstream-Basis ab. Die Fußzeile der Meldungsseiten zeigt Trennstriche nur zwischen vorhandenen Links: kein führender „|“, bei einem Link keiner, bei zwei Links genau einer.

**Blocked by:** None (can start immediately)

**Status:** ready-for-agent

- [ ] Der Diff des Nachrichten-Layouts gegen die Basis enthält nur inhaltliche Änderungen (heute 107 geänderte Zeilen, davon 7 inhaltlich)
- [ ] Trennstrich nur zwischen zwei Links (Muster des Booking-Footers)
- [ ] Reproduktion vor dem Fix bei laufendem Stack: URL für Rechtliche Hinweise setzen, Manage-Link mit unbekanntem Hash öffnen → Fußzeile beginnt mit „|“. Nicht reproduzierbar → Ticket auf `needs-info` statt Fix
- [ ] Smoke-Prüfung nach dem Fix: Die Fußzeile beginnt nicht mit „|“ (0, 1 und 2 Links geprüft)
- [ ] Layout-Smoke-Test, `composer test`, `php -l` grün
