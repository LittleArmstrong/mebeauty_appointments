# 05: Wizard-Card-Kopf konfigurierbar

**What to build:** Die Wizard-Card (Buchungsformular) zeigt wieder das Firmenlogo und den Firmennamen im Kopf (1.6.0-Verhalten), gesteuert durch ein neues Boolean-Setting. Default ist „anzeigen"; „ausblenden" ergibt den aktuellen kompakten Look (nur Schritt-Indikator). Die Farben folgen weiterhin `--bs-primary` (Theme/Firmenfarbe).

**Blocked by:** 01 Generische Defaults wiederherstellen

**Status:** ready-for-agent

- [ ] Der Wizard-Card-Kopf (Logo + Name, dynamisch via `company_logo`/`company_name`) ist wieder vorhanden
- [ ] Ein neues Setting steuert das Anzeigen des Kopfes (Default = anzeigen, 1.6.0-Verhalten)
- [ ] Bei „ausblenden" erscheint nur der Schritt-Indikator (aktueller kompakter Look)
- [ ] Das Setting wird per Migration seedet und ist in der Buchungs-Settings-UI pflegbar
- [ ] Farben (Schritt-Indikator, Auswahl-Highlights) bleiben über `--bs-primary` dynamisch
