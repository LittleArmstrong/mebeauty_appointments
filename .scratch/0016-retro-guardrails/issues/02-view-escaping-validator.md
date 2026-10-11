# 02: Validator für ungeescapte Ausgaben in Fork-Views

**What to build:** `composer check:views` meldet in Fork-Views unter `application/views/` jede Ausgabe `<?= …`, deren Ausdruck mit `$`, `setting(`, `vars(` oder `html_vars(` beginnt, mit Datei und Zeile. Die CI führt den Check aus. Die vorhandenen Stellen in `site_navbar.php`, `site_footer.php` und `booking_company_header.php` geben über `e()` aus.

**Blocked by:** None (can start immediately)

**Status:** resolved

- [x] Reine Prüffunktion in `scripts/validate-view-escaping.php`, testgetrieben mit Unit-Tests (Fälle siehe Spec, Testing Decisions)
- [x] Die CLI wählt nur Fork-Views (seit dem Upstream-Basis-Commit hinzugefügt, plus ungetrackte), Exit 1 bei Treffern
- [x] Composer-Befehl `check:views`; CI-Schritt im Job `build-test`
- [x] Gegen den alten Stand der drei Views rot (Beleg im Kommentar), nach dem Fix grün
- [x] Smoke `mebeauty-layout` bleibt grün

## Comments

- `find_unescaped_view_output()` testgetrieben (`tests/Unit/ViewEscapingValidatorTest.php`): Zyklus 1 (`$`-Ausgabe mit Zeile) und 2 (`setting(`/`vars(`/`html_vars(`) erst rot, dann grün; Zyklus 3 (e/lang/asset_url, ohne Leerzeichen) sichert Bestehendes ab und war sofort grün.
- Rot vor Fix: `composer check:views` gegen die alten Views → 16 Treffer (`site_navbar.php` 5, `site_footer.php` 10, `booking_company_header.php` 1), Exit 1. Nach `e()`: „OK: 6 Fork-Views ohne ungeescapte Ausgabe.“
- Fork-Auswahl über Git wie in `check-format.sh` (Basis aus `UPSTREAM.md`, Exit 2 ohne Basis-Commit); CI-Job `build-test` hat `fetch-depth: 0`.
- Smoke `mebeauty-layout` und `language-selector` grün.
