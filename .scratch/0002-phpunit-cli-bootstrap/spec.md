# PHPUnit-CLI-Bootstrap

Status: ready-for-agent
Created: 2026-09-30
Closed: –
Priority: P2
Depends-on: –

## Problem Statement

Die PHPUnit-Suite brach lokal ab, bevor der erste Test lief: Der Bootstrap lädt `index.php`, und der CodeIgniter-Router interpretierte die PHPUnit-Argumente (`--configuration`, `phpunit.xml`) als Controller/Methode und antwortete mit einem 404.

## Solution

Der Bootstrap darf `index.php` unter PHPUnit nicht über das CLI-Routing führen; normales Web- und CLI-Routing bleibt unverändert. Details im Ticket `01`.

## Further Notes

- Diese Spec wurde nachträglich angelegt (Tracking-Schema `0015`); ursprünglich existierte nur das Ticket.
