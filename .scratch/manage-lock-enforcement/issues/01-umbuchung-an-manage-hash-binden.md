# 01: Umbuchung nur mit gültigem Manage-Hash; Termin- und Kunden-ID serverseitig binden

**What to build:** Der Buchungs-Endpunkt verändert einen bestehenden Termin nur noch, wenn die Anfrage den Hash des Manage-Links mitbringt und dieser zur übermittelten Termin-ID gehört. Termin- und Kunden-ID übernimmt der Server aus dem gespeicherten Termin. Eine Neubuchung ohne Termin-ID funktioniert wie bisher, eine mitgeschickte Kunden-ID wird dabei ignoriert. Ist der Manage-Modus gesetzt oder eine Termin-ID vorhanden, fehlt aber ein passender Hash, wird die Anfrage ohne Seiteneffekte abgelehnt. Sie wird nicht in eine Neubuchung umgewandelt.

**Blocked by:** None (can start immediately)

**Status:** ready-for-agent

- [ ] Der Booking-Flow schickt im Manage-Modus beim Absenden den Termin-Hash mit (aus den bereits übergebenen Termindaten)
- [ ] Der Server prüft das Format des Hashes wie bei der Stornierung (alphanumerisch) und löst ihn über die vorhandene Termin-Abfrage per Hash auf
- [ ] Umbuchung: Die ID des aufgelösten Termins muss gleich der übermittelten Termin-ID sein (falls gesendet); Termin- und Kunden-ID kommen aus dem Datenbank-Termin
- [ ] Neubuchung: Termin-ID und Kunden-ID aus der Anfrage werden verworfen; Kundenzuordnung wie bisher über die E-Mail-Adresse
- [ ] Die Verfügbarkeitsprüfung beim Absenden arbeitet mit dem bereinigten Termin (Übergabe als Parameter statt erneutem Lesen der Anfrage) und schließt nur den gebundenen Original-Termin aus
- [ ] Ablehnung bei fehlendem, unbekanntem oder nicht passendem Hash mit einheitlicher Meldung; nichts gespeichert (kein Kunde, keine Einwilligung, kein Termin, keine Benachrichtigung, kein Sync, kein Webhook)
- [ ] Neues HTTP-Smoke-Skript deckt ab: Neubuchung OK; fremde Termin-ID ohne Hash abgelehnt und fremder Termin unverändert; gültiger Hash mit fremder ID abgelehnt; fremde Kunden-ID bei Neubuchung lässt fremden Kunden unverändert; gültige Umbuchung OK
- [ ] `composer test`, `php -l` und `node --check` für geänderte Dateien, Layout-Smoke-Test grün
