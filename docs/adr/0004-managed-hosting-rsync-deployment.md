# Deployment auf manitu Managed Hosting per GitHub-Actions-rsync (klassisch LAMP)

Die Produktionsumgebung ist manitu Managed Hosting, das keinen Root-Zugang, kein Docker und keine Laufzeit-Build-Tools (composer/npm) bietet. Deshalb wird die App dort klassisch als PHP unter Apache mit MySQL/MariaDB betrieben; das Repo enthält zwar eine `docker-compose.yml`, die aber nur für die lokale Entwicklung gilt.

Der Build (Composer-Abhängigkeiten, SCSS→CSS, Minified JS, `assets/vendor/`) läuft vollständig in GitHub Actions, weil manitu ihn nicht ausführen kann. Das fertige Ergebnis wird per `rsync` über SSH in den Webspace deployt. `config.php` ist gitignored und liegt nur auf dem Server; der Deploy schließt sie aus und fasst den `storage/`-Inhalt nicht an, damit Logs/Uploads/Sessions erhalten bleiben.

Alternativen: ein Docker-basiertes Deployment auf einem eigenen VPS wurde verworfen, weil der Salon bereits manitu nutzt und kein Root-Server verwaltet werden soll; ein SFTP-Deploy wurde zugunsten von rsync verworfen, weil rsync inkrementell und mit `--delete` idempotent arbeitet. Ein Server-seitiges `git pull` scheidet aus, da der Webspace den Build nicht ausführen kann.

Consequences: Prod- und Test-Deploys unterscheiden sich nur durch Zielpfad und Trigger (Test automatisch per `staging`-Branch, Prod manuell). Datenbank-Migrationen bleiben ein bewusster manueller Schritt nach dem Deploy und laufen nicht in der Pipeline, damit die Pipeline keine DB-Zugangsdaten benötigt.
