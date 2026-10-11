# 02: Manage-Frist end-to-end

**What to build:** Die Betreiberin kann für Umbuchen und Stornieren eine eigene Frist setzen (Modi `inherit`/`minutes`/`fixed_time`), die unabhängig von der Neubuchung wirkt. Ohne Konfiguration bleibt das heutige Verhalten exakt erhalten (`inherit`). Kunden sehen bei abgelaufener Frist die Sperrseite; Neubuchungen folgen unverändert der Buchungsfrist.

**Blocked by:** None (can start immediately)

**Status:** resolved

- [ ] Migration 072 fügt die vier Settings `manage_deadline_mode` (`inherit`), `manage_deadline_minutes` (`0`), `manage_deadline_time` (`18:00`), `manage_deadline_days` (`1`) ein; `down()` entfernt sie wieder
- [ ] Der Sperr-Check kennt einen Aktions-Parameter (`booking`|`manage`); `manage` nutzt die `manage_deadline_*`-Settings
- [ ] `inherit` delegiert exakt an die bestehende Buchungsfrist-Logik (beide Modi)
- [ ] `minutes` sperrt, sobald der Terminbeginn innerhalb von `manage_deadline_minutes` liegt; `0` = bis zum Beginn möglich
- [ ] `fixed_time` sperrt nach (Tag D−N) um `manage_deadline_time` (Provider-Zeitzone)
- [ ] Umbuch-Pfad und Storno-Pfad rufen den Sperr-Check mit `manage` auf
- [ ] Die Buchungsfrist sperrt nach der Änderung nur noch Neubuchungen
- [ ] UI-Block „Umbuchungs-/Stornierungsfrist" in den Buchungs-Einstellungen mit Modus-Select und modusabhängigen Feldern (JS-Toggle)
- [ ] de/en-Übersetzungen für alle neuen Keys
- [ ] `composer test` grün; `php -l` und `node --check` je geänderte Datei
