# 02: Nachrichten-Layout auf Upstream-Formatierung zurücksetzen und Trennstriche korrigieren

**What to build:** Das Nachrichten-Layout weicht nur noch inhaltlich von der Upstream-Basis ab. Die Fußzeile der Meldungsseiten zeigt Trennstriche nur zwischen vorhandenen Links: kein führender „|“, bei einem Link keiner, bei zwei Links genau einer.

**Blocked by:** None (can start immediately)

**Status:** resolved

- [x] Der Diff des Nachrichten-Layouts gegen die Basis enthält nur inhaltliche Änderungen (heute 107 geänderte Zeilen, davon 7 inhaltlich)
- [x] Trennstrich nur zwischen zwei Links (Muster des Booking-Footers)
- [x] Reproduktion vor dem Fix bei laufendem Stack: URL für Rechtliche Hinweise setzen, Manage-Link mit unbekanntem Hash öffnen → Fußzeile beginnt mit „|“. Nicht reproduzierbar → Ticket auf `needs-info` statt Fix
- [x] Smoke-Prüfung nach dem Fix: Die Fußzeile beginnt nicht mit „|“ (0, 1 und 2 Links geprüft)
- [x] Layout-Smoke-Test, `composer test`, `php -l` grün

## Comments

- Reproduziert vor dem Fix: Mit gesetzter URL für Rechtliche Hinweise zeigte `/booking/reschedule/<unbekannt>` die Fußzeile „| Rechtlicher Hinweis“.
- Datei aus dem Upstream-Original neu aufgebaut, nur mit den inhaltlichen Fork-Änderungen (Titel mit Firmenname, kein 192-px-Icon, kein „Powered by“): Diff gegen die Basis 12 statt 107 Zeilen. Nach dem Fix geprüft: 0 Links → leer, 1 Link → „Rechtlicher Hinweis“, nur Impressum → „Impressum“, 2 Links → „Rechtlicher Hinweis | Impressum“. Einstellungen danach zurückgesetzt.
