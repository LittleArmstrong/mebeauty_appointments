# 02: Validator für ungeescapte Ausgaben in Fork-Views

**What to build:** `composer check:views` meldet in Fork-Views unter `application/views/` jede Ausgabe `<?= …`, deren Ausdruck mit `$`, `setting(`, `vars(` oder `html_vars(` beginnt, mit Datei und Zeile. Die CI führt den Check aus. Die vorhandenen Stellen in `site_navbar.php`, `site_footer.php` und `booking_company_header.php` geben über `e()` aus.

**Blocked by:** None (can start immediately)

**Status:** ready-for-agent

- [ ] Reine Prüffunktion in `scripts/validate-view-escaping.php`, testgetrieben mit Unit-Tests (Fälle siehe Spec, Testing Decisions)
- [ ] Die CLI wählt nur Fork-Views (seit dem Upstream-Basis-Commit hinzugefügt, plus ungetrackte), Exit 1 bei Treffern
- [ ] Composer-Befehl `check:views`; CI-Schritt im Job `build-test`
- [ ] Gegen den alten Stand der drei Views rot (Beleg im Kommentar), nach dem Fix grün
- [ ] Smoke `mebeauty-layout` bleibt grün
