# 01: Migration: Neue Settings für Frist, Puffer & Timezone

**What to build:** Nach dem Deployment existieren die sieben neuen Settings mit ihren Defaults in der Settings-Tabelle. Das Verhalten der App bleibt unverändert, weil alle Defaults dem heutigen Status quo entsprechen (Minuten-Frist aktiv, Puffer aus, Timezone-Dropdown sichtbar mit Firmen-Default-Quelle).

**Blocked by:** None (can start immediately)

**Status:** resolved

- [ ] Migration mit der Nummer 070 erweitert `EA_Migration` und fügt genau diese Settings mit Defaults ein: `booking_deadline_mode` = `minutes`, `booking_deadline_time` = `18:00`, `booking_deadline_days` = `1`, `appointment_buffer_time` = `0`, `appointment_buffer_end_of_day` = `1`, `booking_timezone_display` = `dropdown`, `booking_timezone_source` = `company`
- [ ] `down()` entfernt genau diese Settings wieder
- [ ] Keine Schema-Änderungen außer Settings-Rows (keine neuen Tabellen/Spalten)
- [ ] Migrationslauf ohne Fehler; Settings sind über das Backend abrufbar
