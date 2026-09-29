# Docker

Docker lets you run Easy!Appointments on your computer without installing Apache, PHP, or MySQL manually. Everything runs inside containers — isolated mini-environments that are easy to start and stop.

## Getting Started

1. Make sure you have [Docker](https://www.docker.com/) installed.
2. Clone or download the project.
3. Create the configuration file if it does not exist yet (it is gitignored):

```bash
cp config-sample.php config.php
```

The sample file already contains the correct Docker values (`BASE_URL=http://localhost`, `DB_HOST=mysql`, `DB_NAME=easyappointments`, `DB_USERNAME=user`, `DB_PASSWORD=password`), so no changes are required for local development.

4. Start everything with:

```bash
docker compose up -d --build
```

The `php-fpm` container automatically installs the Composer/NPM dependencies and compiles the assets on first start (`docker/php-fpm/start-container`), so the initial startup can take a few minutes.

5. Open <http://localhost> and complete the setup wizard (the database is empty on the first run).

## What's Included

Once running, you can access these services:

| Service | URL / Port | Credentials |
|---------|------------|-------------|
| **Easy!Appointments** (booking page) | http://localhost | (your admin account) |
| **Backend** | http://localhost/index.php/calendar | (your admin account) |
| **REST API** | http://localhost/index.php/api/v1/ | API token (Settings → API) |
| **Swagger UI** (API documentation) | http://localhost:8000 | (no login needed) |
| **phpMyAdmin** (database manager) | http://localhost:8080 | `root` / `secret` |
| **MySQL** (database server) | localhost:3306 | `user` / `password` (`root` / `secret`) |
| **Mailpit** (email testing, SMTP on port 1025) | http://localhost:8025 | (no login needed) |
| **Baikal** (CalDAV testing) | http://localhost:8100 | `admin` / `admin` |
| **OpenLDAP** | localhost:389 / 636 | `cn=admin,dc=example,dc=org` / `admin` |
| **phpLDAPadmin** (LDAP testing) | http://localhost:8200 | `cn=admin,dc=example,dc=org` / `admin` |

All ports can be changed with environment variables: `NGINX_PORT`, `MYSQL_PORT`, `PHPMYADMIN_PORT`, `MAILPIT_HTTP_PORT`, `MAILPIT_SMTP_PORT`, `SWAGGER_UI_PORT`, `BAIKAL_PORT`, `OPENLDAP_PORT`, `OPENLDAP_SSL_PORT`, `PHPLDAPADMIN_PORT` (e.g. `NGINX_PORT=8081 docker compose up -d`).

## Development Commands

```bash
docker compose exec php-fpm bash           # shell into the application container
docker compose exec php-fpm npm start      # watch and compile JS/SCSS
docker compose exec php-fpm npm run build  # production assets and easyappointments-0.0.0.zip
docker compose exec php-fpm composer test  # run the PHPUnit test suite
docker compose logs -f                     # follow the container logs
docker compose down                        # stop the environment (DB data stays in ./docker/mysql)
```

## Troubleshooting

If <http://localhost> is not reachable, nginx may have started before PHP-FPM (the Compose file does not define a startup order). Restart it:

```bash
docker compose restart nginx
```

## CalDAV Sync with Baikal

To test CalDAV syncing locally:

1. Open Baikal at http://localhost:8100 and create a new user.
2. In Easy!Appointments, click **Enable Sync** → **CalDAV** and enter:
   - **URL:** `http://baikal/dav.php/calendars/<your-username>/default/`
   - **Username:** your Baikal username
   - **Password:** your Baikal password

## LDAP

OpenLDAP runs on the `openldap` container (ports `389` and `636`). You can manage it through phpLDAPadmin at http://localhost:8200.

> **Note:** This Docker setup is for **development only**. Don't use it in production. For a production Docker image, see: https://github.com/alextselegidis/easyappointments-docker

*This document applies to Easy!Appointments v1.6.0.*

[Back](readme.md)
