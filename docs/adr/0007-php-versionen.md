# PHP 8.2 als Mindestversion, Tests auf PHP 8.3

Produktiv läuft die Kommandozeile bei manitu (SSH, Migrationen, Backups) fest mit PHP 8.2. Der Webserver ist zwischen 8.2 und 8.5 wählbar und läuft mit 8.3. Deshalb ist **8.2 die Mindestversion** für Laufzeitcode und Laufzeitpakete. **Getestet wird auf 8.3**, der Version von Produktiv-Webserver und Docker. Ein eigener CI-Job prüft die Plattformanforderungen der Laufzeitpakete und die Syntax gegen 8.2. Der Deploy-Build installiert ohne Entwicklungspakete und mit Plattformprüfung.

Grund für die Aufteilung: Die Testbibliothek PHPUnit 12 (auch im Upstream) verlangt PHP ≥ 8.3. Verworfen wurde, alles auf 8.2 zu vereinheitlichen. Das hätte ein Downgrade auf PHPUnit 11 und eine globale Plattform-Fixierung in `composer.json` erfordert, also eine dauerhafte Abweichung vom Upstream (ADR 0005). Ebenso verworfen: den Webserver auf 8.2 zu setzen, denn dann liefe er auf einer Version, gegen die keine Tests laufen.

Consequences: Laufzeitunterschiede zwischen 8.2 und 8.3, die über die Syntax hinausgehen, sind für die Kommandozeile nicht durch Tests abgedeckt. Dieses Restrisiko ist bewusst akzeptiert. Wird die Produktiv-CLI angehoben, kann die Mindestversion folgen und der 8.2-Job entfallen.
