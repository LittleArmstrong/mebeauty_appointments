# Eigene MeBeauty-Migrationen 070–073 in eine finale Migration 070 konsolidieren

Status: resolved
Created: 2026-10-10
Closed: 2026-10-10
Priority: –
Depends-on: –

## Problem Statement

Vor dem ersten Production-Release existieren vier eigene MeBeauty-Migrationen
(`070–073`), die alle rein additiv sind und sich nicht gegenseitig aufheben. Da noch
kein Release von der bisherigen Historie abhängt, sollen sie zu **einer** finalen
Migration `070` konsolidiert werden, sodass eine Neuinstallation direkt das finale
Schema erzeugt — ohne überflüssige Zwischenzustände.

## Solution

- Die vier Migrationen `070–073` werden in `070_mebeauty_booking_settings_and_slot_capacity.php`
  zusammengeführt (Klasse `Migration_Mebeauty_booking_settings_and_slot_capacity`).
- `up()` legt 12 Settings an (wenn nicht vorhanden) und fügt `services.slot_capacity`
  (`INT(11) DEFAULT 0`, nach `attendants_number`) hinzu (wenn nicht vorhanden).
- `down()` löscht die 12 Settings und entfernt die Spalte (destruktiv, verlustbehaftet —
  bewusst wie bisher, dokumentiert).
- Upstream-Migrationen `001–069` bleiben unverändert.
- Theme-Default bleibt `default` (Migration `038`, nicht betroffen).

## Enthaltene Änderungen (Alt → Neu)

| Alt | Inhalt | Neu |
|-----|--------|-----|
| 070 | 7 Settings: `booking_deadline_mode`, `booking_deadline_time`, `booking_deadline_days`, `appointment_buffer_time`, `appointment_buffer_end_of_day`, `booking_timezone_display`, `booking_timezone_source` | übernommen |
| 071 | 1 Setting: `booking_show_company_header` = `1` | übernommen |
| 072 | 4 Settings: `manage_deadline_mode`, `manage_deadline_minutes`, `manage_deadline_time`, `manage_deadline_days` | übernommen |
| 073 | Spalte `services.slot_capacity` `INT(11) DEFAULT 0` | übernommen |

Es entfällt nichts.

## Staging-Übergang

Die echte Staging-DB (Tracker `73`) wird nicht angefasst. Der nächste `console migrate`
nach der Konsolidierung setzt den Tracker automatisch von `73` auf `70` ohne Schemaänderung
(CI3 `version(70)` findet nach Löschung von `071–073` keine `down()`-Ziele und schreibt nur
die Version um). Dies wird auf einer isolierten, simulierten v73-DB verifiziert.

## Validation

- PHPUnit vollständig, `php -l` je geänderter Datei.
- `composer check:migrations`, `composer check:assets`.
- Neuinstallation auf isolierter leerer DB → Tracker `70`, 12 Settings korrekt, Spalte vorhanden, Theme `default`.
- Übergangstest v73 → v70 auf isolierter Kopie.

## Out of Scope

- Änderung an Upstream-Migrationen `001–069`.
- Änderung der echten Staging-/Produktiv-DB.
- Theme-Default-Änderung.
