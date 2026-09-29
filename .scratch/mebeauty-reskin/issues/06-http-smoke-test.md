# 06: HTTP-Smoke-Test für den Reskin

**What to build:** Ein automatisierter Smoke-Test prüft gegen den laufenden Docker-Stack die öffentlichen Seiten auf das MeBeauty-Branding (Theme-Link, Dark-Attribut, Nav- und Footer-Links auf die Hauptdomain, Titel, Favicon, Stepper, abwesender Sprach-Umschalter, gebrandete Fehlerseite) und verifiziert über die Mailpit-API, dass ausgelöste E-Mails MeBeauty-Branding mit Gold-Akzenten enthalten. Die bestehende PHPUnit-Suite bleibt grün.

**Blocked by:** 02 (Website-Navbar, Stepper-Leiste und Footer), 03 (Account- und Meldungsseiten), 04 (Fehlerseiten), 05 (E-Mail-Templates).

**Status:** ready-for-agent

- [ ] Smoke-Skript prüft alle öffentlichen Seiten und schlägt bei fehlendem MeBeauty-Branding fehl.
- [ ] E-Mail-Prüfung über die Mailpit-API bestätigt Gold-Akzente und MeBeauty-Footer.
- [ ] Bestehende PHPUnit-Suite bleibt grün.
