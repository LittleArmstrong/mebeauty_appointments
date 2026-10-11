# 05: Wizard-Card-Kopf konfigurierbar

**What to build:** Die Wizard-Card (Buchungsformular) zeigt wieder das Firmenlogo und den Firmennamen im Kopf (1.6.0-Verhalten), gesteuert durch ein neues Boolean-Setting. Default ist „anzeigen"; „ausblenden" ergibt den aktuellen kompakten Look (nur Schritt-Indikator). Die Farben folgen weiterhin `--bs-primary` (Theme/Firmenfarbe).

**Blocked by:** 01 Generische Defaults wiederherstellen

**Status:** resolved

- [x] Der Wizard-Card-Kopf (Logo + Name, dynamisch via `company_logo`/`company_name`) ist wieder vorhanden
- [x] Ein neues Setting steuert das Anzeigen des Kopfes (Default = anzeigen, 1.6.0-Verhalten)
- [x] Bei „ausblenden" erscheint nur der Schritt-Indikator (aktueller kompakter Look)
- [x] Das Setting wird per Migration seedet und ist in der Buchungs-Settings-UI pflegbar
- [x] Farben (Schritt-Indikator, Auswahl-Highlights) bleiben über `--bs-primary` dynamisch

## Comments

- Umgesetzt in a6dba558. Setting `booking_show_company_header` (Migration, Buchungseinstellungen), Kopf im Booking-Layout schaltbar.
