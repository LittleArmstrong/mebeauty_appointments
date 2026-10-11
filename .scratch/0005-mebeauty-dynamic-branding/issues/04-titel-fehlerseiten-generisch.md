# 04: Titel & Fehlerseiten generisch

**What to build:** Alle Seiten- und E-Mail-Titel verwenden den Firmennamen dynamisch mit generischem Fallback (statt „MeBeauty"), und die drei Fehlerseiten lesen den Marken-Namen aus der zentralen Konfigurationskonstante (statt drei Harttexte), so dass sie auch bei defekter DB einen gültigen, zentral änderbaren Marken-Namen zeigen.

**Blocked by:** 01 Generische Defaults wiederherstellen

**Status:** ready-for-agent

- [ ] Backend-, Account-, Message- und Booking-Layout-Titel sowie `og:title` verwenden den Firmennamen mit generischem Fallback (nicht „MeBeauty")
- [ ] Die vier E-Mail-Templates verwenden denselben generischen Firmennamen-Fallback
- [ ] Die drei Fehlerseiten (404, db, general) lesen die Marken-Namen-Konstante statt hart „MeBeauty"
- [ ] Kein hartes „MeBeauty" verbleibt in Titeln/Fehlerseiten; der Wert ist über Settings bzw. Konfiguration steuerbar
