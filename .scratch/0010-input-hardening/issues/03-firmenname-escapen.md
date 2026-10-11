# 03: Firmenname an allen Ausgabestellen escapen

**What to build:** Ein Firmenname mit Sonderzeichen (z. B. `Beauty & "Co" <b>`) erscheint überall korrekt und erzeugt kein kaputtes HTML: Seitentitel, Social-Vorschau, Backend-Kopf und E-Mail-Titel.

**Blocked by:** 0011/02 (Nachrichten-Layout zuerst auf Upstream-Formatierung zurücksetzen)

**Status:** resolved

- [x] Escape-Helper an den zehn Stellen aus der Spec: Booking-Layout (Titel, `og:title`, `og:description`), Titel von Backend-, Konto- und Nachrichten-Layout, Backend-Kopf, Titel der vier E-Mail-Vorlagen
- [x] Probe mit `Beauty & "Co" <b>`: im Quelltext escaped, Darstellung korrekt auf Booking-, Backend-, Konto- und Nachrichtenseite und in allen vier E-Mails (Mailpit)
- [x] Layout-Smoke-Test, E-Mail-Logo-Smoke-Test, `composer test` und `php -l` grün

## Comments

- Escape-Helper an allen Ausgaben ergänzt. Zusätzlich gefunden: `Booking::index()` setzte den Firmennamen in zwei Nachrichten-Zweigen (Termin nicht gefunden, Termin gesperrt) roh in `page_title`, das Nachrichten-Layout gibt `page_title` unescaped aus; an das Upstream-Muster des dritten Zweigs (`e($company_name)`) angeglichen.
- Probe mit `Beauty & "Co" <b>`: Booking-Titel und `og:*`, Nachrichtenseite, Login (Konto-Layout) und Passwort-E-Mail (Mailpit) escaped. Backend-Layout (nur mit Login erreichbar) nicht live geprüft, gleiche Änderung. Firmenname danach zurückgesetzt.
