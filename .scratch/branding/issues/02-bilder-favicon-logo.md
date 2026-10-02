# 02: Bilder: Favicon + App-Logo auf MeBeauty

**What to build:** Alle Seiten verwenden eine einzige Favicon-Quelle (root `favicon.ico`), und das App-Logo ist ein quadratisches MeBeauty-Logo. Dadurch zeigen Backend-Header, Login/Recovery/Logout/Password-Reset, About, das eingebettete E-Mail-Logo und die 192×192-Icons MeBeauty — per Datei-Ersatz, ohne Referenz-Änderungen.

**Blocked by:** None (can start immediately).

**Status:** ready-for-agent

- [ ] Es existiert nur noch eine Favicon-Datei, und alle Layouts referenzieren sie (keine doppelte Kopie)
- [ ] Das App-Logo ist ein quadratisches MeBeauty-Logo (aus `icon-192` auf 128×128 resized), nicht mehr das Easy!Appointments-Logo
- [ ] Das rechteckige Marketing-Wordmark bleibt unangetastet
- [ ] HTTP-Smoke zeigt MeBeauty-Favicon und MeBeauty-App-Logo auf den relevanten Seiten
