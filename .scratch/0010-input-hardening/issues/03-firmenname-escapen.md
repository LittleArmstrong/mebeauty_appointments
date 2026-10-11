# 03: Firmenname an allen Ausgabestellen escapen

**What to build:** Ein Firmenname mit Sonderzeichen (z. B. `Beauty & "Co" <b>`) erscheint überall korrekt und erzeugt kein kaputtes HTML: Seitentitel, Social-Vorschau, Backend-Kopf und E-Mail-Titel.

**Blocked by:** 0011/02 (Nachrichten-Layout zuerst auf Upstream-Formatierung zurücksetzen)

**Status:** ready-for-agent

- [ ] Escape-Helper an den zehn Stellen aus der Spec: Booking-Layout (Titel, `og:title`, `og:description`), Titel von Backend-, Konto- und Nachrichten-Layout, Backend-Kopf, Titel der vier E-Mail-Vorlagen
- [ ] Probe mit `Beauty & "Co" <b>`: im Quelltext escaped, Darstellung korrekt auf Booking-, Backend-, Konto- und Nachrichtenseite und in allen vier E-Mails (Mailpit)
- [ ] Layout-Smoke-Test, E-Mail-Logo-Smoke-Test, `composer test` und `php -l` grün
