<h1 align="center">
    <br>
    <a href="https://easyappointments.org">
        <img src="https://raw.githubusercontent.com/alextselegidis/easyappointments/develop/logo.png" alt="Easy!Appointments" width="150">
    </a>
    <br>
    Easy!Appointments
    <br>
</h1>

<h4 align="center">
    A powerful, self-hosted appointment scheduling platform built for flexibility.
</h4>

<p align="center">
  <img alt="License" src="https://img.shields.io/github/license/alextselegidis/easyappointments?style=for-the-badge">
  <img alt="Latest Release" src="https://img.shields.io/github/v/release/alextselegidis/easyappointments?style=for-the-badge">
  <img alt="Downloads" src="https://img.shields.io/github/downloads/alextselegidis/easyappointments/total?style=for-the-badge">
  <a href="https://discord.com/invite/UeeSkaw">
    <img alt="Discord" src="https://img.shields.io/badge/chat-on%20discord-7289da?style=for-the-badge&logo=discord&logoColor=white">
  </a>
</p>

<p align="center">
  <a href="#why-easyappointments">Why Easy!Appointments</a> •
  <a href="#features">Features</a> •
  <a href="#quick-start">Quick Start</a> •
  <a href="#installation">Installation</a> •
  <a href="#license">License</a>
</p>

---

<p align="center">
  <strong>Looking for advanced capabilities?</strong><br>
  Explore premium features and professional services at
  <a href="https://easyappointments.org/premium" target="_blank">easyappointments.org/premium</a>.
</p>

---

![screenshot](screenshot.png)

## 🚀 Why Easy!Appointments

**Easy!Appointments** is an open-source scheduling system that gives you full control over your booking workflow.

It is designed to adapt to your business — whether you need simple appointment booking or more advanced scheduling logic.

**Key advantages:**

- Fully self-hosted — your data stays under your control
- Highly customizable and flexible
- Integrates with your existing website and database
- Free for both personal and commercial use

---

## ✨ Features

Built to support a wide range of scheduling needs:

- Appointment and customer management
- Service and provider organization
- Working plans and booking rules
- Google Calendar synchronization
- Email notification system
- Multi-language interface
- Self-hosted deployment
- Active open-source community

---

## ⚡ Quick Start (Development)

Local development runs entirely with Docker Compose (Nginx, PHP-FPM, MySQL, Mailpit and more).

### Requirements

* [Docker](https://www.docker.com/) with Docker Compose
* Git

### Start the environment

```bash
# Clone the repository (SSH: git@github.com:LittleArmstrong/mebeauty_appointments.git)
git clone https://github.com/LittleArmstrong/mebeauty_appointments.git

# Navigate into the project
cd mebeauty_appointments

# Only needed if config.php does not exist yet (it is gitignored)
cp config-sample.php config.php

# Start the Docker environment
docker compose up -d --build
```

The `php-fpm` container automatically installs the Composer/NPM dependencies and compiles the assets on first start (see `docker/php-fpm/start-container`), so the initial startup can take a few minutes.

### Open the application

* Booking page: <http://localhost>
* Backend: <http://localhost/index.php/calendar>
* The setup wizard runs on the first visit (empty database).

If <http://localhost> is not reachable, nginx may have started before PHP-FPM (known race condition):

```bash
docker compose restart nginx
```

All local service URLs, ports and credentials are listed in [docs/docker.md](docs/docker.md).

### Development commands

```bash
docker compose exec php-fpm bash           # shell into the application container
docker compose exec php-fpm npm start      # watch and compile JS/SCSS
docker compose exec php-fpm npm run build  # production assets and easyappointments-0.0.0.zip
docker compose exec php-fpm composer test  # run the PHPUnit test suite
docker compose logs -f                     # follow the container logs
docker compose down                        # stop the environment (DB data stays in ./docker/mysql)
```

> Note: Works on Windows (WSL recommended), macOS, and Linux using Docker Compose.

---

## 🏗️ Installation (Production)

### Requirements

* Apache or Nginx
* PHP 8.2+
* MySQL database

### Steps

1. Create a database (or use an existing one)
2. Upload the `easyappointments` folder to your server
3. Ensure the `storage` directory is writable
4. Rename `config-sample.php` to `config.php`
5. Update configuration values
6. Open the application in your browser and follow the setup wizard

Once completed, the system is ready to use.

> Tip: Run `npm run build` to generate a production archive (`easyappointments-0.0.0.zip`) that includes the compiled assets.

---

## 📚 Resources

* Website: [https://easyappointments.org](https://easyappointments.org)
* Issues: [https://github.com/alextselegidis/easyappointments/issues](https://github.com/alextselegidis/easyappointments/issues)
* Support Group: [https://groups.google.com/forum/#!forum/easy-appointments](https://groups.google.com/forum/#!forum/easy-appointments)
* Discord: [https://discord.com/invite/UeeSkaw](https://discord.com/invite/UeeSkaw)

---

## 📜 License

* Code: GPL v3.0
* Content: CC BY 3.0

---

## 👤 Author

* Website: [https://alextselegidis.com](https://alextselegidis.com)
* GitHub: [https://github.com/alextselegidis](https://github.com/alextselegidis)
* Twitter: [https://twitter.com/AlexTselegidis](https://twitter.com/AlexTselegidis)

---

## 🔥 More Projects

* [Plainpad · Self-Hosted Note Taking](https://github.com/alextselegidis/plainpad)
* [Clientverse · CRM Application](https://github.com/alextselegidis/clientverse)
* [Timecrack · Time Tracking](https://github.com/alextselegidis/timecrack)
