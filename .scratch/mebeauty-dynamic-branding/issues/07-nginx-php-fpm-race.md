# 07: nginx/php-fpm-Startreihenfolge fixen

**What to build:** nginx startet erst nach php-fpm und startet sich bei Startreihenfolge-Problemen selbst neu, so dass `localhost` zuverlässig erreichbar ist und der `host not found in upstream "php-fpm"`-Crash nicht mehr auftritt.

**Blocked by:** None (can start immediately).

**Status:** ready-for-agent

- [ ] Der `nginx`-Service in `docker-compose.yml` deklariert `depends_on: [php-fpm]`
- [ ] Der `nginx`-Service hat `restart: unless-stopped`
- [ ] Kein `[emerg] host not found in upstream "php-fpm"` beim Start mehr
- [ ] `docker compose up` führt ohne manuellen nginx-Neustart zu einem erreichbaren `http://localhost`
