# PHPUnit-CLI-Bootstrap

Status: resolved
Created: 2026-09-30
Closed: 2026-10-11
Priority: –
Depends-on: –

## Problem Statement

Die PHPUnit-Suite brach lokal ab, bevor der erste Test lief: Der Bootstrap lädt `index.php`, und der CodeIgniter-Router interpretierte die PHPUnit-Argumente (`--configuration`, `phpunit.xml`) als Controller/Methode und antwortete mit einem 404.

## Solution

Der Bootstrap darf `index.php` unter PHPUnit nicht über das CLI-Routing führen; normales Web- und CLI-Routing bleibt unverändert. Details im Ticket `01`.

## Further Notes

- Diese Spec wurde nachträglich angelegt (Tracking-Schema `0015`); ursprünglich existierte nur das Ticket.
