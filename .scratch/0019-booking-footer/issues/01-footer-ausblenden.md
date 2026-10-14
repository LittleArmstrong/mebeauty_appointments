# 01: Footer leer ausblenden und Einstellung „Footer anzeigen“

**What to build:** Hat der Footer der Wizard-Card nichts anzuzeigen, weil keine Rechts-URL gesetzt ist, der Sprachumschalter aus ist und der Login-Button aus ist, wird er gar nicht gerendert, auch nicht die Trennlinie. In den Buchungseinstellungen gibt es „Footer anzeigen“, standardmäßig an. Steht sie auf aus, fehlt der Footer immer. Das Site-Chrome des Themes ist davon nicht betroffen.

**Blocked by:** 0017/01

**Status:** needs-verification

- [x] Einstellung `display_booking_footer` (0/1, Default 1) in Migration 070 (idempotent), Regel im zentralen Einstellungs-Mechanismus (Unit-Test), pflegbar in den Buchungseinstellungen
- [x] Booking-Layout bindet den Footer nicht ein, wenn er leer ist oder die Einstellung 0 ist (Regel in `Booking_footer_rules`, Unit-Test `BookingFooterRulesTest`)
- [x] Einzelne Footer-Elemente bleiben wie bisher steuerbar
- [x] Deployment-Doku nennt das Ausschalten für MeBeauty (bereits vorbereitet, prüfen)
- [ ] Kein Layout-Smoke; Sichtprüfung durch den Nutzer (Theme `mebeauty` und `default`, Footer an/aus, mit/ohne Elemente)
- [x] `bash scripts/test.sh` grün

## Comments

- Rot vor Fix: `BookingFooterRulesTest` gegen simuliertes Altverhalten (Footer immer gerendert, `is_shown()` liefert `true`) – 6 von 10 Fällen „Failed asserting that true is false.“ (`test_empty_footer_is_not_shown`, `test_footer_with_null_values_is_not_shown`, `test_disabled_setting_hides_footer_with_content` mit allen vier Elementen). Danach 10/10 grün.
- Rot vor Fix: `SettingValidationTest::testInvalidValuesAreRejectedWithSettingName` mit `('display_booking_footer', '2')` ohne die Regel in `validation_helper.php` – „Failed asserting that null is not null.“ Mit Regel grün.
- Review-Befunde zu 8cb97a7f: Sichtbarkeitsentscheidung aus `booking_layout.php` in die Fork-Regel `Booking_footer_rules` verschoben (Layout ruft nur noch auf), Hinweistext „Ist keines dieser Elemente aktiv …“ (de/en angeglichen).
