# MeBeauty-Reskin

Status: ready-for-agent

## Problem Statement

Die Terminbuchungs-App (Easy!Appointments-Fork) trägt noch das Original-Branding und den Standard-Bootstrap-Look: grünes Default-Theme, „Easy!Appointments“-Titel, EA-Logo/-Footer und helle Oberflächen. Besucher, die von der MeBeauty-Website (dunkles Gold-Design, Karla/Dancing Script) zur Buchung wechseln, landen in einer optisch fremden Umgebung. Die Buchung wirkt nicht wie ein Teil der Marke.

## Solution

Die öffentlichen Bereiche der App (Buchung, Login/Account, Bestätigung/Stornierung, Fehlerseiten) und die E-Mail-Templates werden auf das MeBeauty-Erscheinungsbild umgestellt: dunkles Farbschema mit Gold-Akzenten, selbst gehostete Schriften (Karla, Dancing Script), Website-Navbar und -Footer mit Links auf die Hauptdomain, MeBeauty-Logo und -Favicon, deutscher Standard bei erhaltener Mehrsprachigkeits-Fähigkeit (Englisch/Türkisch) sowie rechtliche Links auf die Website-Seiten. Das Backend bleibt funktional unverändert (nur Logo und Browser-Titel werden angepasst).

## User Stories

1. Als Besucher, der von der Website auf die Buchungsseite wechselt, will ich dort dasselbe dunkle/goldene Design wie auf der Website sehen, so dass die Buchung wie ein Teil der Website wirkt.
2. Als Besucher will ich die bekannte Hauptnavigation (Home, Behandlungen, Preise, Kontakt) auf der Buchungsseite sehen, so dass ich jederzeit zur Website zurücknavigieren kann.
3. Als Besucher will ich das MeBeauty-Logo in der Navigation sehen, so dass ich das Branding wiedererkenne.
4. Als Besucher auf dem Smartphone will ich die Navigation über ein Hamburger-Menü bedienen können, so dass sie auch auf kleinen Bildschirmen nutzbar ist.
5. Als Besucher will ich den 4-Schritte-Indikator der Buchung klar sichtbar unter der Navigation sehen, so dass ich jederzeit weiß, wo ich im Buchungsprozess stehe.
6. Als Besucher will ich, dass die Buchungsseite standardmäßig auf Deutsch erscheint, so dass ich sie ohne Sprachwechsel nutzen kann.
7. Als Betreiber will ich später Englisch und Türkisch aktivieren können, ohne Code ändern zu müssen, so dass neue Zielgruppen bedient werden können.
8. Als Besucher will ich im Footer Telefonnummern, E-Mail-Adressen und die Anschrift finden, so dass ich den Salon direkt kontaktieren kann.
9. Als Besucher will ich im Footer die Telefonsprechzeiten sehen, so dass ich weiß, wann ich anrufen kann.
10. Als Besucher will ich im Footer die Social-Media-Profile (Instagram, TikTok, WhatsApp) verlinkt sehen, so dass ich dem Salon folgen kann.
11. Als Besucher will ich im Footer Links zu Impressum und Datenschutz, so dass ich die rechtlichen Seiten der Website erreiche.
12. Als Besucher will ich im Browser-Tab das MeBeauty-Favicon und den Titel „… | MeBeauty“ sehen, so dass die App einheitlich gebrandet ist.
13. Als Besucher will ich nach der Buchung eine Bestätigungsseite im selben Design sehen, so dass der Ablauf konsistent bleibt.
14. Als Besucher will ich auch bei einer Stornierung eine Seite im MeBeauty-Design sehen, so dass kein Fremd-Branding auftaucht.
15. Als Besucher will ich bei Fehlern (z. B. 404) eine Seite mit MeBeauty-Branding sehen, so dass die App nie als „Easy!Appointments“ auftritt.
16. Als Besucher will ich Buchungsbestätigungs-, Stornierungs-, Passwort-Reset- und Konto-Wiederherstellungs-E-Mails mit MeBeauty-Logo und Gold-Akzenten erhalten, so dass auch die E-Mails gebrandet sind.
17. Als Besucher will ich auf der Login-/Passwort-Reset-Seite dasselbe Design wie auf der Buchungsseite sehen, so dass der öffentliche Bereich einheitlich wirkt.
18. Als Besucher will ich über einen dezenten Login-/Backend-Link im Footer in den Admin-Bereich wechseln können, so dass die bestehende Funktion erhalten bleibt.
19. Als Betreiber will ich, dass Behandlungen und Preise nur auf der Hauptwebsite gepflegt werden (keine Duplikate in der App), so dass keine doppelte Pflege entsteht.
20. Als Betreiber will ich die rechtlichen Links als bestehende Einstellungen mit vorausgefüllten Website-Defaults pflegen, so dass keine URLs im Code hartkodiert sind.
21. Als Betreiber will ich ein eigenes Theme „mebeauty“ mit zentralen Design-Tokens, so dass Farben und Schriften an einer Stelle geändert werden können.
22. Als Betreiber will ich, dass die Schriften selbst gehostet werden, so dass keine externen Google-Fonts-Requests nötig sind.
23. Als Backend-Nutzer will ich, dass das Backend funktional unverändert bleibt (nur Logo und Tab-Titel angepasst), so dass der Admin-Bereich stabil bleibt.
24. Als Backend-Nutzer will ich, dass bestehende Einstellungen (Firmenfarbe, Firmenlogo, Theme-Auswahl) weiterhin funktionieren, so dass Anpassungen weiterhin ohne Code möglich sind.
25. Als Entwickler will ich einen HTTP-Smoke-Test für die öffentlichen Seiten, so dass der Reskin automatisiert verifizierbar ist.
26. Als Entwickler will ich, dass der Asset-Build weiterhin fehlerfrei läuft, so dass Deployments nicht brechen.

## Implementation Decisions

- Bootstrap-Theme „mebeauty“ als einziges Design für den öffentlichen Bereich. Design-Tokens: Primary `#d1ae5e` (Gold), Akzent `#FFCC66` (Goldenrod), Hintergrund Schwarz/`#26241e`, Text Weiß/`#e5e7eb`, Radius 0.5rem, Buttons und Formulare im Website-Stil (goldene Rahmen auf Schwarz). Dark-Scheme über das Bootstrap-Dark-Attribut in den öffentlichen Layouts.
- Öffentliche Layouts binden das mebeauty-Theme fest ein; das Backend behält seine eigene Theme-Auswahl (Standardwert unverändert). Kein globaler Theme-Default-Wechsel, damit das Backend unberührt bleibt.
- Schriften Karla (Fließtext) und Dancing Script (Überschriften) werden selbst gehostet (WOFF2) und per `@font-face` im Theme eingebunden.
- Gemeinsame Navbar- und Footer-Komponenten für alle öffentlichen Seiten; die bestehende Template-Engine (Layouts/Sections/Komponenten) bleibt unverändert.
- Navbar: Logo + Links zu den vier Hauptseiten der Website (absolut auf die Hauptdomain), mobiles Hamburger-Menü. Kein „Termin buchen“-Button (folgt später auf der Hauptdomain) und kein schwebender WhatsApp-Button.
- Der Buchungs-Stepper wird aus dem bisherigen Kopfbereich in eine goldene Leiste unter der Navbar verschoben; der Firmenname entfällt aus dem Buchungs-Kopfbereich (er bleibt für Browser-Titel und E-Mails erhalten).
- Footer: drei Spalten (Marke + Social-Icons, Kontaktdaten, Telefonsprechzeiten) plus untere Zeile (Markenname, Impressum, Datenschutz, Login/Backend-Link). Keine Behandlungsliste im Footer.
- Sprachstrategie: Deutsch als Standard (Default-Sprache per Migration, Fallback-Konstante auf Deutsch). Sprach-Umschalter über ein Konfigurations-Flag deaktiviert, später aktivierbar; die vorhandenen Englisch-/Türkisch-Übersetzungen bleiben erhalten. Neue MeBeauty-UI-Strings werden in Deutsch, Englisch und Türkisch gepflegt. Mehrsprachigkeit der Hauptwebsite ist ein separates Projekt.
- Neue Migration: setzt die Default-Sprache auf Deutsch und befüllt die beiden rechtlichen URL-Einstellungen (Datenschutz und Impressum der Website) nur falls leer.
- Firmenfarbe bleibt ohne neuen Standardwert, damit das Backend nicht mitgefärbt wird.
- Branding-Assets: MeBeauty-Logo ersetzt das globale App-Logo; Favicon wird global ersetzt; neue Apple-Touch-Icons; Social-Media-Icons; Social-Share-Grafik und Meta-Theme-Farbe angepasst.
- Browser-Titel der öffentlichen Seiten und des Backends enden auf „MeBeauty“ (rein kosmetisch, keine funktionale Auswirkung).
- E-Mail-Templates: helles Layout mit Gold-Akzenten (bewusste Abweichung vom dunklen Website-Look für Lesbarkeit/Zustellbarkeit); Logo über die Firmenlogo-Einstellung mit MeBeauty-Fallback; Footer mit MeBeauty-Branding.
- Cookie-Hinweis-Palette auf Gold.
- Fehlerseiten erhalten MeBeauty-Branding; die Installationsseite bleibt unverändert.
- Neue zentrale Konfiguration für Website-Basis-URL, Navigationslinks, Kontaktdaten, Social-Links und das Sprach-Flag.
- Keine Inhalts-Duplikate zwischen Website und App: Verlinkung statt Kopie von Inhalten.

## Testing Decisions

- Gute Tests prüfen nur externes Verhalten (HTTP-Antworten, gerendertes Markup, Build-Artefakte, ausgelieferte E-Mail-Inhalte), keine Implementierungsdetails.
- Hauptseam (neu, höchster Punkt): HTTP-Smoke-Test gegen den laufenden Docker-Stack. Geprüft werden: HTTP 200 der öffentlichen Seiten, dunkles Design-Attribut, Verweis auf das kompilierte mebeauty-Theme-Stylesheet, Navbar- und Footer-Links auf die Hauptdomain, Browser-Titel mit „MeBeauty“, Favicon-Referenz, deutsche Sprachattribute, vorhandener Buchungs-Stepper, abwesender Sprach-Umschalter; Fehlerseite (404) mit MeBeauty-Branding.
- E-Mail-Seam (ebenfalls über HTTP): ausgelöste E-Mails enthalten laut Mailpit-API MeBeauty-Branding und Gold-Akzente.
- Asset-Build-Seam (bestehend): der Asset-Build läuft fehlerfrei durch und erzeugt das kompilierte mebeauty-Theme.
- Die bestehende PHPUnit-Suite bleibt grün.
- Prior Art: bestehende PHPUnit-Unit-Tests sowie die manuelle Docker-Verifikation aus der README.

## Out of Scope

- Backend-Restyling (Dunkel-Modus für Admin, Navigation, Backend-Footer, Premium-Link) – bleibt wie es ist.
- Installationsseite.
- Schwebender WhatsApp-Button und „Termin buchen“-Button in der App (folgt später auf der Hauptdomain).
- Behandlungs- und Preislisten in der App (bleiben auf der Website).
- Mehrsprachigkeit der Hauptwebsite (Astro-Projekt, separates Vorhaben).
- Änderungen an der Verwaltung von Firmenfarbe und Firmenlogo.

## Further Notes

- Das Glossar (Website vs. App, öffentliche Seiten, Backend, Theme, Firmenfarben-Einstellung, Sprach-Flag) wird im Rahmen der Umsetzung angelegt; zwei ADRs werden vorgeschlagen: (1) Bootstrap-Theme statt Tailwind für den Reskin, (2) Theme-Trennung öffentlich/Backend plus Sprachstrategie.
- Rechtlicher Hinweis (kein Rechtsrat): Die Datenschutzerklärung der Website sollte die Buchung auf der Subdomain abdecken; eine juristische Gegenprüfung wird empfohlen. Das Impressum der Website deckt die Subdomain ab (gleicher Betreiber).
- Kontaktdaten und Behandlungstitel stammen aus der Website (Quelle: Kontakt-JSON und Inhaltskollektionen der Website), werden aber nicht in die App kopiert, sondern verlinkt.
