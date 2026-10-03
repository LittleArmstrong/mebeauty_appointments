# 01: Generische Defaults wiederherstellen (Theme, Logo, Markenname)

**What to build:** Eine frische Installation sieht wieder wie Easy!Appointments 1.6.0 aus: das Default-Theme ist `default` (nicht `mebeauty`), das Standard-App-Logo `assets/img/logo.png` ist wieder das generische Easy!Appointments-Logo (nicht das MeBeauty-Logo), und es gibt eine zentrale Konfigurationskonstante für den Marken-Namen (analog `DEFAULT_COMPANY_COLOR`), die ohne DB-Zugriff verfügbar ist.

**Blocked by:** None (can start immediately).

**Status:** ready-for-agent

- [ ] Das `theme`-Setting hat den Default `default`; `mebeauty` bleibt als Option wählbar (Umkehrung der Migration `070` über eine neue Migration, bestehende Migrationsdateien unverändert)
- [ ] `assets/img/logo.png` ist wieder das generische Easy!Appointments-Logo (nicht das MeBeauty-Logo); MeBeauty-Logos bleiben nur unter `assets/img/mebeauty/`
- [ ] Eine Konfigurationskonstante (analog `DEFAULT_COMPANY_COLOR`) liefert den Marken-Namen-Fallback
- [ ] Die bestehenden unkommittierten Änderungen an `assets/img/mebeauty/logo-128.png`/`logo-192.png` sowie `logo-128_old.png`/`logo-192_old.png` bleiben unangetastet
- [ ] HTTP-Smoke/Prüfsumme belegen: generisches Logo als Default, `mebeauty` nicht mehr der Theme-Default
