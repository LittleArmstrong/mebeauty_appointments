# Booking-Footer ausblenden: leer automatisch, per Einstellung immer

Status: ready-for-agent
Created: 2026-10-13
Closed: –
Priority: P3
Depends-on: 0017

## Problem Statement

Der Footer der Wizard-Card enthält nur drei Dinge: die Links „Rechtliche Hinweise“ und „Impressum“ (jeweils nur mit gesetzter URL), den Sprachumschalter und den Login-Button (`application/views/components/booking_footer.php:14-44`). Das Booking-Layout rendert ihn immer (`application/views/layouts/booking_layout.php:66-71`), samt Trennlinie (`application/views/components/booking_footer.php:12`).

Bei MeBeauty sind keine Rechts-URLs gesetzt, und der Sprachumschalter ist ausgeschaltet (0014). Ohne Login-Button bleibt ein leerer Streifen mit Trennlinie unter dem Wizard. Ganz abschalten lässt sich der Footer nicht.

## Solution

Ist im Footer nichts anzuzeigen, entfällt er ganz, samt Trennlinie. Eine neue Einstellung „Footer anzeigen“ (Standard: an, wie bei Upstream) blendet ihn unabhängig vom Inhalt immer aus.

## User Stories

1. Als Salonbetreiberin will ich keinen leeren Footer mit Trennlinie unter dem Wizard sehen, damit die Wizard-Card sauber abschließt.
2. Als Salonbetreiberin will ich den Footer in den Buchungseinstellungen ganz ausschalten können, damit Kunden weder Login-Button noch Sprachumschalter sehen.
3. Als Admin einer Installation ohne Anpassung will ich, dass der Footer standardmäßig angezeigt wird, damit sich das Upstream-Verhalten nicht ändert.
4. Als Admin will ich, dass Rechts-Links, Sprachumschalter und Login-Button bei eingeschaltetem Footer wie bisher einzeln steuerbar bleiben.
5. Als Mitarbeiterin will ich das Backend weiterhin direkt über die Login-Adresse erreichen, auch wenn der Footer aus ist.
6. Als Salonbetreiberin will ich die Einstellung später wieder einschalten können, ohne Code zu ändern.

## Implementation Decisions

- **Einstellung** `display_booking_footer`:
  - Werte 0/1, Standard 1. Sie steht in den Buchungseinstellungen und wird über den zentralen Regel-Mechanismus aus 0010 als 0/1 geprüft.
  - Angelegt wird sie in Migration 070, nach dem Konsolidierungsprinzip aus 0017. Nach dem Deploy auf Test und lokal: `migrations.version` auf 69 setzen, dann `console migrate`.
  - Für MeBeauty wird sie beim Deployment auf 0 gesetzt, nicht per Migration.
- **Footer leer:** keine Rechts-URL, Sprachumschalter aus, Login-Button aus. Das Booking-Layout entscheidet das aus denselben Werten, die es heute an den Footer übergibt. Bei leerem Footer oder Einstellung 0 bindet es den Footer nicht ein.
- Das Site-Chrome des Themes (eigener Site-Footer) ist davon nicht betroffen.
- Die bestehenden Einstellungen für die Elemente bleiben unverändert.

## Testing Decisions

- Kein automatischer Test für das Layout. Der Nutzer prüft Theme-Layout und Texte visuell (AGENTS.md).
- Die Migration prüft der bestehende Migrations-Validator. Die Regel für die neue Einstellung kommt in die vorhandenen Tests des Einstellungs-Regel-Mechanismus, sofern es dort Tests pro Einstellung gibt.
- Manuell: Booking-Flow mit Footer an/aus, mit und ohne Login-Button, Sprachumschalter und Rechts-URL. Theme `mebeauty` und `default`.

## Out of Scope

- Der Site-Footer des Themes (0014).
- Weitere Footer-Inhalte.
- Andere Layouts (Backend, Konto, Nachrichten).

## Further Notes

- Herkunft: Grilling am 2026-10-11, zusammen mit 0017.
- Hängt nur wegen der Migrationskonsolidierung von 0017 ab (071 ist seit 0017/01 in 070 aufgegangen; neue Einstellungen kommen in 070).
- Bezug: 0010, 0014, ADR 0005, Glossar „Wizard-Card“, „Booking-Flow“.
