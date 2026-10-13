# 04: Backend zeigt „unbestätigt“

**What to build:** Im Backend-Kalender erscheinen Vormerkungen mit dem Zusatz „unbestätigt“ und dem Namen aus den Termin-Kontaktdaten. So vergibt das Personal den Slot nicht telefonisch doppelt. Das Personal kann eine Vormerkung löschen, bestätigen kann es sie nicht. Abgelaufene Vormerkungen verschwinden durch das Aufräumen. In Google erscheint eine Vormerkung erst nach der Bestätigung.

**Blocked by:** 01, 0017/05

**Status:** ready-for-agent

- [ ] Kachel und Dialog kennzeichnen Vormerkungen als „unbestätigt“ (Texte Deutsch/Englisch)
- [ ] Löschen einer Vormerkung entfernt auch ihre Bestätigungsdaten
- [ ] Eine Vormerkung ohne Kunde bricht weder Kalender noch Dialog
- [ ] Kein Google-Sync für Vormerkungen
- [ ] Manuelle Sichtprüfung durch den Nutzer
- [ ] `bash scripts/test.sh` grün
