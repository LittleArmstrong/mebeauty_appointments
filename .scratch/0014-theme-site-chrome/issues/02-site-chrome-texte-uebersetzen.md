# 02: Site-Chrome-Texte über Übersetzungen

**What to build:** Alle Texte im MeBeauty-Site-Chrome (auch der Seitentitel „Buchung“ und versteckte Beschriftungen wie „Menü öffnen“) kommen aus den Übersetzungen, in Deutsch und Englisch. Bei deutscher Sprache ist die Seite unverändert.

**Blocked by:** 01 (Site-Chrome ins Theme mebeauty verschieben)

**Status:** resolved

- [x] Neue Übersetzungsschlüssel für alle Site-Chrome-Texte in Deutsch und Englisch
- [x] Verhalten fehlender Schlüssel in anderen Sprachen geprüft; bei Bedarf englischer Text in allen Sprachdateien
- [x] Im Site-Chrome stehen keine fest codierten Texte mehr (Kundendaten wie Adresse und Telefonnummer bleiben)
- [x] Manuelle Probe: Mit Englisch erscheinen Navbar und Footer englisch
- [x] Layout-Smoke-Test, PHPUnit und `php -l` grün

## Comments

- 16 neue Schlüssel `site_*` (Deutsch/Englisch) für Seitentitel, Social-Vorschau, Navbar, Footer-Überschriften, Hinweis, Rechtslinks und Screenreader-Beschriftungen. Website-Inhalte (Behandlungsnamen, Adresse, Telefonzeiten, Links) bleiben laut Spec im Theme.
- Befund: Für fehlende Schlüssel gab es keine Rückfallsprache – `lang()` lieferte den Schlüsselnamen. Das betraf auch alle bisherigen Fork-Schlüssel in den 38 übrigen Sprachen. `lang()` fällt jetzt auf Englisch zurück (statt Schlüssel in 40 Upstream-Dateien zu kopieren).
- Geprüft: Deutsch unverändert (Smoke 21/21), `?language=english` vollständig englisch, `?language=french` fällt für die neuen Texte auf Englisch zurück.
