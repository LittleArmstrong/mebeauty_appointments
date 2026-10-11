# 07: nginx/php-fpm-Startreihenfolge fixen

**What to build:** nginx startet erst nach php-fpm und startet sich bei Startreihenfolge-Problemen selbst neu, so dass `localhost` zuverlässig erreichbar ist und der `host not found in upstream "php-fpm"`-Crash nicht mehr auftritt.

**Blocked by:** None (can start immediately).

**Status:** resolved

- [x] Der `nginx`-Service in `docker-compose.yml` deklariert `depends_on: [php-fpm]`
- [x] Der `nginx`-Service hat `restart: unless-stopped`
- [x] Kein `[emerg] host not found in upstream "php-fpm"` beim Start mehr
- [x] `docker compose up` führt ohne manuellen nginx-Neustart zu einem erreichbaren `http://localhost`

## Comments

- Umgesetzt in a6dba558. nginx hat `depends_on: php-fpm` und `restart: unless-stopped`; `docker compose up` startet ohne Fehler. Beobachtung 2026-10-11: Nach einem Neustart des Hosts startet nur nginx automatisch neu (php-fpm hat keine Restart-Policy) und meldet dann „host not found“ – außerhalb dieses Tickets, `docker compose up -d` behebt es.
