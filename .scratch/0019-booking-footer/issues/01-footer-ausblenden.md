# 01: Footer leer ausblenden und Einstellung „Footer anzeigen“

**What to build:** Hat der Footer der Wizard-Card nichts anzuzeigen, weil keine Rechts-URL gesetzt ist, der Sprachumschalter aus ist und der Login-Button aus ist, wird er gar nicht gerendert, auch nicht die Trennlinie. In den Buchungseinstellungen gibt es „Footer anzeigen“, standardmäßig an. Steht sie auf aus, fehlt der Footer immer. Das Site-Chrome des Themes ist davon nicht betroffen.

**Blocked by:** 0017/01

**Status:** needs-verification

- [x] Einstellung `display_booking_footer` (0/1, Default 1) in Migration 070 (idempotent), Regel im zentralen Einstellungs-Mechanismus (Unit-Test), pflegbar in den Buchungseinstellungen
- [x] Booking-Layout bindet den Footer nicht ein, wenn er leer ist oder die Einstellung 0 ist
- [x] Einzelne Footer-Elemente bleiben wie bisher steuerbar
- [x] Deployment-Doku nennt das Ausschalten für MeBeauty (bereits vorbereitet, prüfen)
- [ ] Kein Layout-Smoke; Sichtprüfung durch den Nutzer (Theme `mebeauty` und `default`, Footer an/aus, mit/ohne Elemente)
- [ ] `bash scripts/test.sh` grün (nicht gelaufen: Docker-Stack mountet nur den Haupt-Checkout; nach Merge im Haupt-Checkout ausführen)
