# Booking-Frist, Pufferzeit & Timezone-Anzeige — Implementation Plan

> **Hinweis (Migration-Nummerierung):** Dieser Plan nennt die Migrationen noch mit ihren historischen Nummern (`071`, `070_set_mebeauty_default_theme.php`). Nach der Bereinigung (siehe ADR 0005) ist `071_add_booking_deadline_and_buffer_settings.php` heute `070_…`, und die Theme-Migrationen `070_set_mebeauty_default_theme`/`072_revert_…` wurden entfernt.

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Drei konfigurierbare Buchungssteuerungen ergänzen: Frist als Entweder/Oder (Minuten-Vorlaufzeit ODER feste Uhrzeit + Tagesabstand), globale Pufferzeit nach Terminen (optional am Tagesende) und eine konfigurierbare Zeitzonen-Anzeige im Buchungs-Wizard.

**Architecture:** Alle Regeln wirken zentral in der Availability-Library (`get_available_hours()`), die von Wizard, Datepicker, API v1 und serverseitiger Buchungsvalidierung gemeinsam genutzt wird. Der einzige Frist-Pfad außerhalb ist der Manage-Mode-Lock im Booking-Controller (Storno/Umbuchen). Sieben neue Settings (nur `settings`-Tabelle, Migration 071) steuern das Verhalten; die UI liegt in den Business Settings.

**Tech Stack:** PHP 8.2+ / CodeIgniter 3 (Easy!Appointments-Fork, `EA_*`-Klassen), MySQL, jQuery/Bootstrap 5, Gulp-Build für JS (`npm run build`), PHPUnit 12 (`composer test`), Docker-Compose-Dev-Umgebung.

## Global Constraints

- Spec: `.scratch/0003-booking-deadline-buffer-timezone/spec.md` — Ticket-Zerlegung: `.scratch/0003-booking-deadline-buffer-timezone/issues/01..07` (eine Task pro Ticket, Reihenfolge = Abhängigkeiten).
- Es werden **keine neuen PHPUnit-Unit-Tests** für die Availability-Library geschrieben (Spec-Entscheidung: keine DB-Fixture-Infrastruktur). Verifikation = `php -l`, `composer test`, `npm run build` + manuelle Checkliste mit konkretem DB/UI-Setup.
- **Commits:** Gemäß AGENTS.md darf NICHT automatisch committet werden. Jede Task endet mit einem Checkpoint: `git status`/`git diff` zeigen, Commit-Befehl vorschlagen, aber erst nach ausdrücklicher Nutzer-Freigabe ausführen.
- Vor Beginn: Task-Branch anlegen: `git checkout -b agent/booking-deadline-buffer-timezone`.
- Frist-Semantik `fixed_time`: Termin am Tag D buchbar, solange „jetzt“ ≤ (Tag D − N) um HH:MM (Provider-Zeitzone); danach ist der **gesamte** Tag D gesperrt. Keine Mindestvorlaufzeit im Festzeit-Modus; vergangene Stunden immer gefiltert. Modus `minutes` = bestehende Logik unverändert. **Nie kombiniert.**
- Puffer: global, nur **nach** echten Terminen (nie davor, nie nach Unavailabilities/Blocked Periods); Tagesende optional über eigenes Setting.
- Frist/Puffer wirken nur kundenseitig (Wizard + API). Backend-Kalender bleibt unverändert.
- Neue Sprach-Keys nur in `german` + `english`. Domänen-Vokabular: Vorlaufzeit, Buchungsfrist, Tagesabstand, Pufferzeit (Glossar).
- JS-Dateien haben `.min.js`-Gegenstücke; nach jeder JS-Änderung `npm run build`.
- Dev-Umgebung: `docker compose up -d`, App unter `http://localhost` (Basis-URL ggf. anpassen, in Verifikationsschritten `$BASE` genannt).

---

## File Structure

| Datei | Verantwortung |
|---|---|
| `application/migrations/071_add_booking_deadline_and_buffer_settings.php` (neu) | Legt die 7 Settings mit Defaults an (Task 1) |
| `application/libraries/Availability.php` | Frist-Modus (Task 2), Pufferzeit (Task 5) |
| `application/language/german/translations_lang.php`, `english/…` | Neue Keys + korrigiertes Label (Task 3) |
| `GLOSSARY.md` | 4 neue kanonische Begriffe (Task 3) |
| `docs/adr/0002-booking-deadline-and-buffer.md` (neu) | Begründung Frist-Modi + Puffer-Semantik (Task 3) |
| `application/controllers/Booking.php` | Manage-Mode-Lock mit Frist (Task 4), `booking_timezone_display/source` in script_vars (Task 7) |
| `application/views/pages/business_settings.php` + `assets/js/pages/business_settings.js` | Settings-UI mit Modus-Umschaltung (Task 6) |
| `application/views/components/booking_time_step.php` + `assets/js/pages/booking.js` | TZ-Anzeige-Modi im Wizard (Task 7) |

---

### Task 1: Migration 071 — 7 Settings

**Files:**
- Create: `application/migrations/071_add_booking_deadline_and_buffer_settings.php`

**Interfaces:**
- Consumes: Muster von `application/migrations/070_set_mebeauty_default_theme.php` (Klasse `extends EA_Migration`).
- Produces: Setting-Namen + Defaults für alle Folge-Tasks: `booking_deadline_mode`=`minutes`, `booking_deadline_time`=`18:00`, `booking_deadline_days`=`1`, `appointment_buffer_time`=`0`, `appointment_buffer_end_of_day`=`1`, `booking_timezone_display`=`dropdown`, `booking_timezone_source`=`company`.

- [ ] **Step 1: Migration schreiben**

```php
<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_booking_deadline_and_buffer_settings extends EA_Migration
{
    /**
     * Upgrade method.
     */
    public function up(): void
    {
        $settings = [
            'booking_deadline_mode' => 'minutes',
            'booking_deadline_time' => '18:00',
            'booking_deadline_days' => '1',
            'appointment_buffer_time' => '0',
            'appointment_buffer_end_of_day' => '1',
            'booking_timezone_display' => 'dropdown',
            'booking_timezone_source' => 'company',
        ];

        foreach ($settings as $name => $value) {
            if (!$this->db->get_where('settings', ['name' => $name])->num_rows()) {
                $this->db->insert('settings', ['name' => $name, 'value' => $value]);
            }
        }
    }

    /**
     * Downgrade method.
     */
    public function down(): void
    {
        $this->db
            ->where_in('name', array_keys([
                'booking_deadline_mode' => 'minutes',
                'booking_deadline_time' => '18:00',
                'booking_deadline_days' => '1',
                'appointment_buffer_time' => '0',
                'appointment_buffer_end_of_day' => '1',
                'booking_timezone_display' => 'dropdown',
                'booking_timezone_source' => 'company',
            ]))
            ->delete('settings');
    }
}
```

- [ ] **Step 2: Syntax prüfen**

Run: `php -l application/migrations/071_add_booking_deadline_and_buffer_settings.php`
Expected: `No syntax errors detected`

- [ ] **Step 3: Migration ausführen**

Migrations laufen automatisch beim Laden einer Backend-Seite (`application/libraries/Instance.php` ruft `$this->CI->migration->latest()`). Dev-Umgebung starten (`docker compose up -d`, falls nicht aktiv) und im Browser eine Backend-Seite öffnen (z. B. `$BASE/index.php/backend/login`), damit `latest()` ausgeführt wird.

- [ ] **Step 4: Rows verifizieren**

Run (DB-Zugangsdaten aus `docker-compose.yml` ableiten, DB-Name ist der Projektname):

```bash
docker compose exec db mysql -u<user> -p<password> <dbname> -e \
"SELECT name, value FROM settings WHERE name IN ('booking_deadline_mode','booking_deadline_time','booking_deadline_days','appointment_buffer_time','appointment_buffer_end_of_day','booking_timezone_display','booking_timezone_source') ORDER BY name;"
```

Expected: alle 7 Rows mit exakt den Defaults aus Step 1.

- [ ] **Step 5: Bestehende Tests laufen lassen**

Run: `composer test`
Expected: vorhandene Tests grün (nur Helper-Tests).

- [ ] **Step 6: Checkpoint (Commit nur nach Freigabe)**

```bash
git status --short
git diff --stat
```

Vorgeschlagene Commit-Message: `feat: add booking deadline, buffer and timezone settings migration (071)`

---

### Task 2: Frist-Modus in der Availability-Library

**Files:**
- Modify: `application/libraries/Availability.php:588-612` (`consider_book_advance_timeout`)

**Interfaces:**
- Consumes: Settings aus Task 1 (`booking_deadline_mode`, `booking_deadline_time`, `booking_deadline_days`); bestehende `setting()`-Hilfsfunktion.
- Produces: `public function get_booking_deadline(string $date, string $timezone): ?DateTime` — liefert im Modus `fixed_time` den Frist-Zeitpunkt für Tag D (D − N Tage um HH:MM in der übergebenen Zeitzone), sonst `null`. Task 4 konsumiert diese Methode.

- [ ] **Step 1: Öffentliche Deadline-Methode einfügen**

Direkt vor `consider_book_advance_timeout` (Zeile 588) einfügen:

```php
    /**
     * Get the booking deadline for the selected date.
     *
     * In fixed time mode the deadline of day D is (D - deadline days) at the deadline time. In minutes mode
     * there is no such deadline and null is returned.
     *
     * @param string $date Selected date (Y-m-d).
     * @param string $timezone Timezone used for the deadline calculation.
     *
     * @return DateTime|null Returns the deadline moment, or null when the minutes mode is active.
     *
     * @throws Exception
     */
    public function get_booking_deadline(string $date, string $timezone): ?DateTime
    {
        if (setting('booking_deadline_mode', 'minutes') !== 'fixed_time') {
            return null;
        }

        $deadline_time = (string) setting('booking_deadline_time', '18:00');

        $deadline_days = max(0, (int) setting('booking_deadline_days', '1'));

        [$hour, $minute] = array_pad(explode(':', $deadline_time), 2, '0');

        $deadline = new DateTime($date, new DateTimeZone($timezone));

        $deadline->setTime((int) $hour, (int) $minute, 0);

        $deadline->modify('-' . $deadline_days . ' days');

        return $deadline;
    }
```

- [ ] **Step 2: `consider_book_advance_timeout` umschreiben**

Den bestehenden Methoden-Body (Zeilen 588–612, exakter alter Code oben im Original) ersetzen durch:

```php
    protected function consider_book_advance_timeout(string $date, array $available_hours, array $provider): array
    {
        $provider_timezone = new DateTimeZone($provider['timezone']);

        $threshold = new DateTime('now', $provider_timezone);

        $deadline = $this->get_booking_deadline($date, $provider['timezone']);

        if ($deadline !== null) {
            // Fixed time mode: once the deadline has passed the whole day is locked. No minimum lead
            // time applies in this mode, only hours that lie in the past are filtered below.
            if (new DateTime('now', $provider_timezone) > $deadline) {
                return [];
            }
        } else {
            // Minutes mode: keep the existing book advance timeout behavior.
            $book_advance_timeout = setting('book_advance_timeout', 0);

            $book_advance_timeout = is_numeric($book_advance_timeout) ? max(0, (int) $book_advance_timeout) : 0;

            $threshold->modify('+' . $book_advance_timeout . ' minutes');
        }

        foreach ($available_hours as $index => $value) {
            $available_hour = new DateTime($date . ' ' . $value, $provider_timezone);

            if ($available_hour->getTimestamp() <= $threshold->getTimestamp()) {
                unset($available_hours[$index]);
            }
        }

        $available_hours = array_values($available_hours);

        sort($available_hours, SORT_STRING);

        return array_values($available_hours);
    }
```

- [ ] **Step 3: Syntax + Tests**

Run: `php -l application/libraries/Availability.php` und `composer test`
Expected: keine Syntaxfehler, Tests grün.

- [ ] **Step 4: Manuell verifizieren (Modus fixed_time)**

Setup per SQL (Tagesangaben relativ zum heutigen Datum wählen):

```bash
docker compose exec db mysql ... -e "UPDATE settings SET value='fixed_time' WHERE name='booking_deadline_mode'; UPDATE settings SET value='18:00' WHERE name='booking_deadline_time'; UPDATE settings SET value='1' WHERE name='booking_deadline_days';"
```

Prüfen im Wizard (`$BASE/index.php/booking`):
- Morgen ist im Datumswähler ausgegraut und liefert keine Stunden; übermorgen liefert Stunden.
- Gleiche Prüfung über die API: `$BASE/index.php/booking/get_available_hours` (POST) liefert für morgen `[]`.

Dann `booking_deadline_days='0'` und `booking_deadline_time='08:00'`: heute liefert Slots nur vor 08:00 (bei Arbeitszeiten 10–19 faktisch keine — Morgen bleibt voll buchbar).

- [ ] **Step 5: Regression (Modus minutes)**

```bash
docker compose exec db mysql ... -e "UPDATE settings SET value='minutes' WHERE name='booking_deadline_mode';"
```

Wizard-Verhalten muss exakt dem vorherigen Zustand entsprechen (Minuten-Vorlaufzeit greift wie bisher).

- [ ] **Step 6: Checkpoint (Commit nur nach Freigabe)**

Vorgeschlagene Commit-Message: `feat: support fixed time booking deadline in availability`

---

### Task 3: Sprache, Glossar & ADR

**Files:**
- Modify: `application/language/german/translations_lang.php` (im Bereich Zeilen 54–55 und 179–181)
- Modify: `application/language/english/translations_lang.php` (analoge Stellen)
- Modify: `GLOSSARY.md`
- Create: `docs/adr/0002-booking-deadline-and-buffer.md`

**Interfaces:**
- Consumes: Setting-Namen aus Task 1.
- Produces: Lang-Keys für Tasks 4, 6 (exakte Key-Liste unten). Sperr-Message-Key `appointment_locked_message_deadline` mit Platzhalter `{$limit}`.

- [ ] **Step 1: Deutsche Keys ergänzen und Label korrigieren**

In `german/translations_lang.php` Zeile 54–55 ergänzen (nach `appointment_locked_message`):

```php
$lang['appointment_locked_message_deadline'] = 'Der Termin kann nicht mehr geändert oder storniert werden. Buchungen und Änderungen sind bis {$limit} möglich.';
```

Zeilen 179–181 ersetzen:

```php
$lang['book_advance_timeout'] = 'Mindestvorlaufzeit';
$lang['book_advance_timeout_hint'] = 'Legen Sie fest, wie viele Minuten ein Termin mindestens in der Zukunft liegen muss, bevor Kunden ihn buchen oder ändern können.';
$lang['timeout_minutes'] = 'Vorlaufzeit (Minuten)';
```

Danach die neuen Keys einfügen:

```php
$lang['booking_deadline_mode'] = 'Frist-Modus';
$lang['booking_deadline_mode_hint'] = 'Wählen Sie, ob die Buchungsfrist über eine Minuten-Vorlaufzeit oder über eine feste Uhrzeit mit Tagesabstand gesteuert wird. Beide Modi können nicht kombiniert werden.';
$lang['booking_deadline_mode_minutes'] = 'Minuten-Vorlaufzeit';
$lang['booking_deadline_mode_fixed_time'] = 'Feste Uhrzeit';
$lang['deadline_time'] = 'Frist-Uhrzeit';
$lang['deadline_time_hint'] = 'Uhrzeit, bis zu der Termine am jeweiligen Tag buchbar sind.';
$lang['deadline_days'] = 'Tagesabstand';
$lang['deadline_days_hint'] = 'Wie viele Tage vor dem Termin die Frist liegt (0 = gleicher Tag, 1 = Vortag, ...).';
$lang['appointment_buffer_time'] = 'Pufferzeit (Minuten)';
$lang['appointment_buffer_time_hint'] = 'Zeit nach einem Termin, in der kein weiterer Termin gebucht werden kann (0 = deaktiviert).';
$lang['appointment_buffer_end_of_day'] = 'Puffer auch am Tagesende anwenden';
$lang['appointment_buffer_end_of_day_hint'] = 'Der letzte buchbare Slot endet um die Pufferzeit früher vor Arbeitsende.';
$lang['booking_timezone_display'] = 'Zeitzonen-Anzeige im Buchungs-Wizard';
$lang['booking_timezone_display_hint'] = 'Legt fest, wo die Zeitzone im Buchungs-Wizard angezeigt wird.';
$lang['booking_timezone_display_dropdown'] = 'Dropdown und Bestätigungs-Frame';
$lang['booking_timezone_display_confirm_only'] = 'Nur im Bestätigungs-Frame';
$lang['booking_timezone_display_hidden'] = 'Komplett ausblenden';
$lang['booking_timezone_source'] = 'Zeitzonen-Quelle';
$lang['booking_timezone_source_hint'] = 'Welche Zeitzone bei ausgeblendetem Dropdown verwendet wird.';
$lang['booking_timezone_source_company'] = 'Firmen-Zeitzone';
$lang['booking_timezone_source_browser'] = 'Browser-Zeitzone des Kunden';
```

- [ ] **Step 2: Englische Keys ergänzen**

In `english/translations_lang.php` analog (nach `appointment_locked_message`):

```php
$lang['appointment_locked_message_deadline'] = 'The appointment can no longer be changed or cancelled. Bookings and changes are possible until {$limit}.';
```

Im Bereich `book_advance_timeout` (Zeilen 179–181) ersetzen:

```php
$lang['book_advance_timeout'] = 'Book Advance Timeout';
$lang['book_advance_timeout_hint'] = 'Define how many minutes an appointment must lie in the future before customers can book or change it.';
$lang['timeout_minutes'] = 'Lead time (minutes)';
```

Danach:

```php
$lang['booking_deadline_mode'] = 'Deadline mode';
$lang['booking_deadline_mode_hint'] = 'Choose whether the booking deadline is controlled by a minutes lead time or by a fixed time with a day offset. The two modes cannot be combined.';
$lang['booking_deadline_mode_minutes'] = 'Minutes lead time';
$lang['booking_deadline_mode_fixed_time'] = 'Fixed time';
$lang['deadline_time'] = 'Deadline time';
$lang['deadline_time_hint'] = 'Time of day until which appointments for the respective day can be booked.';
$lang['deadline_days'] = 'Day offset';
$lang['deadline_days_hint'] = 'How many days before the appointment the deadline lies (0 = same day, 1 = previous day, ...).';
$lang['appointment_buffer_time'] = 'Buffer time (minutes)';
$lang['appointment_buffer_time_hint'] = 'Time after an appointment during which no further appointment can be booked (0 = disabled).';
$lang['appointment_buffer_end_of_day'] = 'Apply buffer at the end of the day as well';
$lang['appointment_buffer_end_of_day_hint'] = 'The last bookable slot ends the buffer time earlier before the end of the working day.';
$lang['booking_timezone_display'] = 'Timezone display in the booking wizard';
$lang['booking_timezone_display_hint'] = 'Defines where the timezone is shown in the booking wizard.';
$lang['booking_timezone_display_dropdown'] = 'Dropdown and confirmation frame';
$lang['booking_timezone_display_confirm_only'] = 'Only in the confirmation frame';
$lang['booking_timezone_display_hidden'] = 'Hide completely';
$lang['booking_timezone_source'] = 'Timezone source';
$lang['booking_timezone_source_hint'] = 'Which timezone is used when the dropdown is hidden.';
$lang['booking_timezone_source_company'] = 'Company timezone';
$lang['booking_timezone_source_browser'] = "Customer's browser timezone";
```

- [ ] **Step 3: Glossar erweitern**

In `GLOSSARY.md` unter `## Language` ergänzen (Format wie bestehende Einträge, kein Implementierungsdetail):

```markdown
**Vorlaufzeit (Lead Time)**:
Mindestabstand in Minuten zwischen dem aktuellen Zeitpunkt und dem Beginn eines buchbaren Termins.
_Avoid_: Zeitbegrenzung einer Buchung

**Buchungsfrist (Booking Deadline)**:
Zeitpunkt (feste Uhrzeit plus Tagesabstand), bis zu dem ein Termin an einem bestimmten Tag gebucht oder storniert werden kann; danach ist der Tag gesperrt.
_Avoid_: Buchungslimit, Buchungsschluss

**Tagesabstand (Day Offset)**:
Anzahl Tage N vor dem Termintag, an dem die Buchungsfrist liegt (Frist für Tag D = Tag D−N um die Frist-Uhrzeit).
_Avoid_: Vorlauftage, Offset-Tage

**Pufferzeit (Buffer Time)**:
Globale Ruhe-/Aufräumzeit nach einem Termin, in der kein neuer Termin beginnen darf; optional gilt sie auch vor dem Arbeitsende.
_Avoid_: Aufräumzeit, Abstandszeit
```

- [ ] **Step 4: ADR 0002 schreiben**

Inhalt (Format wie `docs/adr/0001-mebeauty-scss-theme.md`): Kontext (lokaler Salon, keine Online-Termine); Entscheidung 1: Frist als Entweder/Oder-Modus — Minuten-Vorlaufzeit ODER feste Uhrzeit + Tagesabstand, keine Kombination, keine Mindestvorlaufzeit im Festzeit-Modus; Entscheidung 2: Puffer global statt pro Service, nur nach Terminen (nie davor, nicht nach Unavailabilities/Blocked Periods), Tagesende als separate Option; betrachtete Alternativen und Konsequenzen (u. a. dass im Festzeit-Modus gleichtägige Buchungen bei Tagesabstand 1 unmöglich sind).

- [ ] **Step 5: Syntax prüfen**

Run: `php -l application/language/german/translations_lang.php` und `php -l application/language/english/translations_lang.php`
Expected: keine Syntaxfehler.

- [ ] **Step 6: Checkpoint (Commit nur nach Freigabe)**

Vorgeschlagene Commit-Message: `docs: add translations, glossary terms and ADR for booking deadline and buffer`

---

### Task 4: Frist-Modus — Sperre für Storno/Umbuchen

**Files:**
- Modify: `application/controllers/Booking.php:247-281` (Manage-Mode-Lock im `index()`)

**Interfaces:**
- Consumes: `Availability::get_booking_deadline(string $date, string $timezone): ?DateTime` (Task 2), Lang-Keys `appointment_locked_message` / `appointment_locked_message_deadline` mit `{$limit}` (Task 3), Helper `format_date_time()` aus `date_helper.php`.
- Produces: Gesperrte Manage-Seite mit Datum + Uhrzeit der Frist im Festzeit-Modus.

- [ ] **Step 1: Lock-Block ersetzen**

Der alte Block (Original, ab `// Make sure the appointment can still be rescheduled.` bis zum schließenden `}` des `if ($appointment_start < $limit)`-Blocks vor `$customer = $this->customers_model->find(...)`) wird ersetzt durch:

```php
            // Make sure the appointment can still be rescheduled or cancelled.

            $provider_timezone = new DateTimeZone($provider['timezone']);

            $appointment_start = new DateTime($appointment['start_datetime'], $provider_timezone);

            $deadline = $this->availability->get_booking_deadline(
                $appointment_start->format('Y-m-d'),
                $provider['timezone'],
            );

            if ($deadline !== null) {
                // Fixed time mode: the whole day is locked once the deadline has passed.
                $is_locked = new DateTime('now', $provider_timezone) > $deadline;

                $limit_text = format_date_time($deadline);

                $locked_message = lang('appointment_locked_message_deadline');
            } else {
                // Minutes mode: keep the existing advance timeout behavior.
                $limit = new DateTime('now', $provider_timezone);

                $limit->modify('+' . $book_advance_timeout . ' minutes');

                $is_locked = $appointment_start < $limit;

                $hours = floor($book_advance_timeout / 60);

                $minutes = $book_advance_timeout % 60;

                $limit_text = sprintf('%02d:%02d', $hours, $minutes);

                $locked_message = lang('appointment_locked_message');
            }

            if ($is_locked) {
                html_vars([
                    'show_message' => true,
                    'page_title' => lang('page_title') . ' ' . $company_name,
                    'message_title' => lang('appointment_locked'),
                    'message_text' => strtr($locked_message, [
                        '{$limit}' => $limit_text,
                    ]),
                    'message_icon' => base_url('assets/img/error.png'),
                    'google_analytics_code' => $google_analytics_code,
                    'matomo_analytics_url' => $matomo_analytics_url,
                    'matomo_analytics_site_id' => $matomo_analytics_site_id,
                    'display_login_button' => $display_login_button,
                    'legal_notice_url' => $legal_notice_url,
                    'imprint_url' => $imprint_url,
                ]);

                $this->load->view('pages/booking_message');

                return;
            }
```

Hinweis: `$book_advance_timeout` (Zeile 197) bleibt unverändert bestehen; `$this->availability` ist im Constructor bereits geladen (Zeile 80).

- [ ] **Step 2: Syntax + Tests**

Run: `php -l application/controllers/Booking.php` und `composer test`
Expected: keine Syntaxfehler, Tests grün.

- [ ] **Step 3: Manuell verifizieren**

1. Modus `fixed_time`, Frist 18:00, Tagesabstand 1 setzen (SQL wie Task 2 Step 4).
2. Einen Termin für übermorgen über den Wizard buchen; den Manage-Link aus der Bestätigungs-E-Mail/Seite merken.
3. Frist „vorbei“ simulieren: `UPDATE settings SET value='00:01' WHERE name='booking_deadline_time';` (oder Tagesabstand erhöhen).
4. Manage-Link öffnen → Sperrseite mit Titel `appointment_locked` und Text `appointment_locked_message_deadline`, der die formatierte Frist (Datum + Uhrzeit im Firmenformat) enthält.
5. Frist zurücksetzen → Manage-Link öffnet wieder den Umbuch-Wizard.
6. Regression: Modus `minutes` → Sperr-Message unverändert (alte `{$limit}`-Stunden-Message).

- [ ] **Step 4: Checkpoint (Commit nur nach Freigabe)**

Vorgeschlagene Commit-Message: `feat: block rescheduling and cancellation after fixed time deadline`

---

### Task 5: Pufferzeit in der Availability-Library

**Files:**
- Modify: `application/libraries/Availability.php` — `get_available_periods()` (Zeilen 363–369), `generate_available_hours()` (Zeilen 559–561), `consider_multiple_attendants()` (Zeilen 151–182), plus neuer Helper `get_buffer_time()`

**Interfaces:**
- Consumes: Settings `appointment_buffer_time`, `appointment_buffer_end_of_day` (Task 1).
- Produces: `protected function get_buffer_time(): int` — normalisierter Pufferwert; intern von allen drei Methoden genutzt. Kein neues öffentliches Interface.

- [ ] **Step 1: Helper `get_buffer_time()` einfügen**

Vor `consider_book_advance_timeout` einfügen:

```php
    /**
     * Get the normalized global buffer time in minutes.
     *
     * @return int
     */
    protected function get_buffer_time(): int
    {
        $buffer_time = setting('appointment_buffer_time', 0);

        return is_numeric($buffer_time) ? max(0, (int) $buffer_time) : 0;
    }
```

- [ ] **Step 2: Termin-Enden in `get_available_periods()` verlängern**

Den Block (Zeilen 363–369):

```php
        $appointments = array_values(
            array_merge(
                $this->CI->appointments_model->get($where),
                $this->CI->unavailabilities_model->get($where),
                $this->CI->blocked_periods_model->get_for_period($date, $date),
            ),
        );
```

ersetzen durch:

```php
        $buffer_time = $this->get_buffer_time();

        $appointments = array_values($this->CI->appointments_model->get($where));

        if ($buffer_time > 0) {
            // The buffer reserves clean-up time after each real appointment. Unavailabilities and
            // blocked periods are explicit blocks and are not extended.
            foreach ($appointments as &$appointment) {
                $appointment_end = new DateTime($appointment['end_datetime']);

                $appointment_end->modify('+' . $buffer_time . ' minutes');

                $appointment['end_datetime'] = $appointment_end->format('Y-m-d H:i:s');
            }

            unset($appointment);
        }

        $appointments = array_values(
            array_merge(
                $appointments,
                $this->CI->unavailabilities_model->get($where),
                $this->CI->blocked_periods_model->get_for_period($date, $date),
            ),
        );
```

- [ ] **Step 3: Tagesende-Puffer in `generate_available_hours()`**

Zeilen 559–561:

```php
            $diff = $current_hour->diff($end_hour);

            while ($diff->h * 60 + $diff->i >= (int) $service['duration'] && $diff->invert === 0) {
```

ersetzen durch:

```php
            $diff = $current_hour->diff($end_hour);

            $end_of_day_buffer = setting('appointment_buffer_end_of_day', '1') === '1' ? $this->get_buffer_time() : 0;

            while (
                $diff->h * 60 + $diff->i >= (int) $service['duration'] + $end_of_day_buffer &&
                $diff->invert === 0
            ) {
```

- [ ] **Step 4: Puffer in `consider_multiple_attendants()`**

Die Schleife (Zeilen 151–182) ersetzen durch (Konfliktfenster startet um Puffer früher; Tagesende-Bedingung berücksichtigt Puffer):

```php
            $buffer_time = $this->get_buffer_time();

            $end_of_day_buffer = setting('appointment_buffer_end_of_day', '1') === '1' ? $buffer_time : 0;

            while ($slot_end->getTimestamp() + $end_of_day_buffer * 60 <= $period['end']->getTimestamp()) {
                // The buffer after existing appointments shifts the conflict window backwards.
                $conflict_start = clone $slot_start;

                if ($buffer_time > 0) {
                    $conflict_start->modify('-' . $buffer_time . ' minutes');
                }

                // Make sure there is no other service appointment for this time slot.
                $other_service_attendants_number = $this->CI->appointments_model->get_other_service_attendants_number(
                    $conflict_start,
                    $slot_end,
                    $service['id'],
                    $provider['id'],
                    $exclude_appointment_id,
                );

                if ($other_service_attendants_number > 0) {
                    $slot_start->add($interval);
                    $slot_end->add($interval);
                    continue;
                }

                // Check reserved attendants for this time slot and see if current attendants fit.
                $appointment_attendants_number = $this->CI->appointments_model->get_attendants_number_for_period(
                    $conflict_start,
                    $slot_end,
                    $service['id'],
                    $provider['id'],
                    $exclude_appointment_id,
                );

                if ($appointment_attendants_number < $service['attendants_number']) {
                    $hours[] = $slot_start->format('H:i');
                }

                $slot_start->add($interval);
                $slot_end->add($interval);
            }
```

- [ ] **Step 5: Syntax + Tests**

Run: `php -l application/libraries/Availability.php` und `composer test`
Expected: keine Syntaxfehler, Tests grün.

- [ ] **Step 6: Manuell verifizieren**

1. Im Backend einen Termin 10:00–11:00 bei einem Provider anlegen (Service-Dauer 60).
2. `UPDATE settings SET value='15' WHERE name='appointment_buffer_time';`
3. Wizard: für diesen Tag/Provider/Service erscheinen 11:00–11:15 nicht (erst ab 11:15, je nach Slot-Intervall); vor 10:00 bleibt alles buchbar.
4. Tagesende aktiv (Default 1), Arbeitszeit 10–19, Dauer 60, Puffer 15 → letzter Slot 17:45. Dann `appointment_buffer_end_of_day='0'` → letzter Slot wieder 18:00.
5. Puffer `0` → Verhalten wie vorher (keine Lücken).
6. Regression: Unavailability 14:00–15:00 anlegen → Puffer verlängert das Sperrende NICHT über 15:00 hinaus.
7. Serverseitig: Buchung eines Slots innerhalb des Puffers direkt per `register()`-Request wird abgelehnt (`requested_hour_is_unavailable`).

- [ ] **Step 7: Checkpoint (Commit nur nach Freigabe)**

Vorgeschlagene Commit-Message: `feat: add global buffer time between appointments`

---

### Task 6: Business-Settings-UI für Frist, Puffer & Timezone

**Files:**
- Modify: `application/views/pages/business_settings.php:97-127` (Frist-Block ersetzen/erweitern)
- Modify: `assets/js/pages/business_settings.js` (Toggle-Logik + Initialisierung)

**Interfaces:**
- Consumes: Lang-Keys aus Task 3 (exakte Namen siehe unten), generische `[data-field]`-Serialisierung (`serialize()`/`deserialize()` in `business_settings.js` — unverändert).
- Produces: DOM-IDs `#booking-deadline-mode`, `#minutes-mode-fields`, `#fixed-time-fields`, `#booking-deadline-time`, `#booking-deadline-days`, `#appointment-buffer-time`, `#appointment-buffer-end-of-day`, `#booking-timezone-display`, `#booking-timezone-source`; JS-Funktion `updateDeadlineModeFields()`.

- [ ] **Step 1: View-Block ersetzen**

Den Block von `<h5 class="mb-3 fw-light"><?= lang('allow_rescheduling_cancellation_before') ?></h5>` bis zum schließenden `</div>` nach dem `book-advance-timeout`-Input (Original Zeilen 97–112) ersetzen durch:

```php
                        <h5 class="mb-3 fw-light"><?= lang('booking_deadline_mode') ?></h5>

                        <div class="mb-5">
                            <label for="booking-deadline-mode" class="form-label">
                                <?= lang('booking_deadline_mode') ?>
                            </label>
                            <select id="booking-deadline-mode" data-field="booking_deadline_mode" class="form-select">
                                <option value="minutes"><?= lang('booking_deadline_mode_minutes') ?></option>
                                <option value="fixed_time"><?= lang('booking_deadline_mode_fixed_time') ?></option>
                            </select>
                            <div class="form-text text-muted">
                                <small>
                                    <?= lang('booking_deadline_mode_hint') ?>
                                </small>
                            </div>

                            <div id="minutes-mode-fields" class="mt-3">
                                <label for="book-advance-timeout" class="form-label">
                                    <?= lang('timeout_minutes') ?>
                                </label>
                                <input id="book-advance-timeout" data-field="book_advance_timeout" class="form-control"
                                       type="number" min="15">
                                <div class="form-text text-muted">
                                    <small>
                                        <?= lang('book_advance_timeout_hint') ?>
                                    </small>
                                </div>
                            </div>

                            <div id="fixed-time-fields" class="mt-3">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="booking-deadline-time" class="form-label">
                                            <?= lang('deadline_time') ?>
                                        </label>
                                        <input id="booking-deadline-time" data-field="booking_deadline_time"
                                               class="form-control" type="time">
                                        <div class="form-text text-muted">
                                            <small>
                                                <?= lang('deadline_time_hint') ?>
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label for="booking-deadline-days" class="form-label">
                                            <?= lang('deadline_days') ?>
                                        </label>
                                        <input id="booking-deadline-days" data-field="booking_deadline_days"
                                               class="form-control" type="number" min="0">
                                        <div class="form-text text-muted">
                                            <small>
                                                <?= lang('deadline_days_hint') ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h5 class="mb-3 fw-light"><?= lang('appointment_buffer_time') ?></h5>

                        <div class="mb-5">
                            <label for="appointment-buffer-time" class="form-label">
                                <?= lang('appointment_buffer_time') ?>
                            </label>
                            <input id="appointment-buffer-time" data-field="appointment_buffer_time"
                                   class="form-control" type="number" min="0">
                            <div class="form-text text-muted">
                                <small>
                                    <?= lang('appointment_buffer_time_hint') ?>
                                </small>
                            </div>
                            <div class="form-check mt-2">
                                <input id="appointment-buffer-end-of-day" data-field="appointment_buffer_end_of_day"
                                       class="form-check-input" type="checkbox">
                                <label for="appointment-buffer-end-of-day" class="form-check-label">
                                    <?= lang('appointment_buffer_end_of_day') ?>
                                </label>
                            </div>
                            <div class="form-text text-muted">
                                <small>
                                    <?= lang('appointment_buffer_end_of_day_hint') ?>
                                </small>
                            </div>
                        </div>

                        <h5 class="mb-3 fw-light"><?= lang('booking_timezone_display') ?></h5>

                        <div class="mb-5">
                            <label for="booking-timezone-display" class="form-label">
                                <?= lang('booking_timezone_display') ?>
                            </label>
                            <select id="booking-timezone-display" data-field="booking_timezone_display"
                                    class="form-select">
                                <option value="dropdown"><?= lang('booking_timezone_display_dropdown') ?></option>
                                <option value="confirm_only"><?= lang('booking_timezone_display_confirm_only') ?></option>
                                <option value="hidden"><?= lang('booking_timezone_display_hidden') ?></option>
                            </select>
                            <div class="form-text text-muted">
                                <small>
                                    <?= lang('booking_timezone_display_hint') ?>
                                </small>
                            </div>

                            <label for="booking-timezone-source" class="form-label mt-3">
                                <?= lang('booking_timezone_source') ?>
                            </label>
                            <select id="booking-timezone-source" data-field="booking_timezone_source"
                                    class="form-select">
                                <option value="company"><?= lang('booking_timezone_source_company') ?></option>
                                <option value="browser"><?= lang('booking_timezone_source_browser') ?></option>
                            </select>
                            <div class="form-text text-muted">
                                <small>
                                    <?= lang('booking_timezone_source_hint') ?>
                                </small>
                            </div>
                        </div>
```

- [ ] **Step 2: Toggle-Logik im JS ergänzen**

In `business_settings.js` eine Funktion vor `function initialize()` einfügen:

```js
    /**
     * Show the fields that belong to the selected deadline mode.
     */
    function updateDeadlineModeFields() {
        const $mode = $('#booking-deadline-mode');

        $('#minutes-mode-fields').prop('hidden', $mode.val() !== 'minutes');
        $('#fixed-time-fields').prop('hidden', $mode.val() !== 'fixed_time');
    }
```

In `initialize()` direkt nach `deserialize(businessSettings);` einfügen:

```js
        updateDeadlineModeFields();

        $('#booking-deadline-mode').on('change', updateDeadlineModeFields);
```

- [ ] **Step 3: Build**

Run: `npm run build`
Expected: Build läuft fehlerfrei, `assets/js/pages/business_settings.min.js` wurde aktualisiert.

- [ ] **Step 4: Syntax + Tests**

Run: `php -l application/views/pages/business_settings.php` und `composer test`
Expected: keine Fehler, Tests grün.

- [ ] **Step 5: Manuell verifizieren**

1. Backend → Einstellungen → Business: Modus-Select auf `fixed_time` → Uhrzeit-/Tagesabstand-Felder erscheinen, Minuten-Feld verschwindet (und umgekehrt).
2. Werte ändern (z. B. Frist 20:00, Tagesabstand 2, Puffer 10, Tagesende-Checkbox aus, TZ-Anzeige `confirm_only`, Quelle `browser`) → Speichern → Seite neu laden: alle Werte korrekt angezeigt.
3. Checkbox-Wert wird als 0/1 gespeichert (nach Neuladen korrekt gecheckt).
4. Keine Doppel-Einträge in der gespeicherten Settings-Liste (DB prüfen, kein Duplikat pro Name).

- [ ] **Step 6: Checkpoint (Commit nur nach Freigabe)**

Vorgeschlagene Commit-Message: `feat: add business settings UI for deadline, buffer and timezone`

---

### Task 7: Timezone-Anzeige im Buchungs-Wizard

**Files:**
- Modify: `application/controllers/Booking.php` (script_vars um zwei Keys erweitern)
- Modify: `application/views/components/booking_time_step.php:22-30`
- Modify: `assets/js/pages/booking.js:171-173` und `:729-750`

**Interfaces:**
- Consumes: Settings `booking_timezone_display`, `booking_timezone_source` (Task 1), `default_timezone` (bereits in script_vars).
- Produces: script_vars-Keys `booking_timezone_display`, `booking_timezone_source`; DOM-Wrapper `#booking-timezone-block`.

- [ ] **Step 1: script_vars erweitern**

In `Booking.php` im `script_vars([...])`-Aufruf (Bereich Zeilen 296–312, nach `'default_timezone' => setting('default_timezone'),`) ergänzen:

```php
            'booking_timezone_display' => setting('booking_timezone_display', 'dropdown'),
            'booking_timezone_source' => setting('booking_timezone_source', 'company'),
```

- [ ] **Step 2: View-Komponente anpassen**

In `booking_time_step.php` den Block Zeilen 22–30 ersetzen durch:

```php
                    <?php $timezone_display = setting('booking_timezone_display', 'dropdown'); ?>
                    <div id="booking-timezone-block" class="mb-3" <?= $timezone_display === 'dropdown' ? '' : 'hidden' ?>>
                        <label for="select-timezone" class="form-label">
                            <?= lang('timezone') ?>
                        </label>
                        <?php component('timezone_dropdown', [
                            'attributes' => 'id="select-timezone" class="form-select mb-3" value="UTC"',
                            'grouped_timezones' => $grouped_timezones,
                        ]); ?>
                    </div>
```

Wichtig: Das `<select id="select-timezone">` bleibt im DOM (nur ausgeblendet), damit `booking_http_client.js:104` und `booking.js` weiterhin einen Wert lesen.

- [ ] **Step 3: Autodetect-Guard in `booking.js`**

Zeilen 171–173:

```js
        const browserTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        const isTimezoneSupported = $selectTimezone.find(`option[value="${browserTimezone}"]`).length > 0;
        $selectTimezone.val(isTimezoneSupported ? browserTimezone : 'UTC');
```

ersetzen durch:

```js
        const timezoneDisplay = vars('booking_timezone_display');
        const timezoneSource = vars('booking_timezone_source');

        if (timezoneDisplay === 'dropdown' || timezoneSource === 'browser') {
            const browserTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
            const isTimezoneSupported = $selectTimezone.find(`option[value="${browserTimezone}"]`).length > 0;
            $selectTimezone.val(isTimezoneSupported ? browserTimezone : 'UTC');
        } else {
            $selectTimezone.val(vars('default_timezone'));
        }
```

- [ ] **Step 4: Bestätigungs-Frame-Zeile konditional rendern**

Zeile 729 ersetzen durch:

```js
        const timezoneRow = vars('booking_timezone_display') === 'hidden'
            ? ''
            : `<div class="mb-2">
                    <i class="fas fa-globe me-2"></i>
                    ${$selectTimezone.find('option:selected').text()}
                </div>`;
```

Zeilen 747–750 (die statische `<div class="mb-2"><i class="fas fa-globe me-2"></i>${timezoneOptionText}</div>`) ersetzen durch `${timezoneRow}`.

- [ ] **Step 5: Build + Syntax + Tests**

Run: `npm run build` und `php -l application/controllers/Booking.php` und `php -l application/views/components/booking_time_step.php` und `composer test`
Expected: Build und Checks fehlerfrei; `booking.min.js`/`booking_http_client.min.js` gebaut.

- [ ] **Step 6: Manuell verifizieren (alle Kombinationen)**

1. `hidden` + `company`: Wizard zeigt kein TZ-Dropdown, keine TZ-Zeile im Bestätigungs-Frame; Zeiten entsprechen `default_timezone`. Buchung erfolgreich durchführen.
2. `confirm_only`: Dropdown ausgeblendet, TZ-Zeile im Bestätigungs-Frame sichtbar.
3. `dropdown`: Verhalten wie vorher (Browser-Autodetect vorbelegt).
4. `hidden` + `browser`: Zeiten in Browser-Zeitzone (Autodetect aktiv, unsichtbar).
5. In allen Kombinationen bleibt die Slot-Umrechnung in `booking_http_client.js` korrekt (Stunden stimmen mit Datumswähler-Auswahl überein).

- [ ] **Step 7: Checkpoint (Commit nur nach Freigabe)**

Vorgeschlagene Commit-Message: `feat: make booking wizard timezone display configurable`

---

## Self-Review

**Spec coverage:**
- Frist-Modus Entweder/Oder + Semantik → Task 2 (Availability), Task 4 (Sperre) ✓
- Ganzer Tag gesperrt, keine Vorlaufzeit im Festzeit-Modus → Task 2 Step 2 (Kommentar + Logik) ✓
- Datepicker-Ausgrauung/API/serverseitige Validierung → automatisch über `get_available_hours()` (Task 2, Task 5) ✓
- Sperr-Message mit Datum + Uhrzeit → Task 4 + Task 3 Key ✓
- Puffer nach Terminen / Tagesende optional / 0 = aus / Multi-Attendant / keine Unavailability-Verlängerung → Task 5 Steps 2–4 + Verifikation ✓
- TZ-Anzeige 3 Zustände + Quelle company/browser → Task 7 ✓
- UI in Business Settings mit Modus-Umschaltung → Task 6 ✓
- Nur DE/EN, Label „Mindestvorlaufzeit“, Glossar, ADR 0002 → Task 3 ✓
- Migration mit 7 Settings → Task 1 ✓

**Type/name consistency:** `get_booking_deadline` (Task 2 Produces = Task 4 Consumes) ✓; Lang-Key-Liste Task 3 = in Task 4 (`appointment_locked_message_deadline`) und Task 6 (alle UI-Keys) identisch verwendet ✓; `get_buffer_time()` in allen drei Task-5-Methoden ✓; `booking_timezone_display/source` als Setting-Name und script_vars-Key identisch ✓.

**Abweichung vom Spec (bewusst):** Keine.
