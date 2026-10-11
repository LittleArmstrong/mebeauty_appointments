<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Booking policy library.
 *
 * Single implementation of the customer-facing booking rules: the booking deadline (new bookings), the manage
 * deadline (rescheduling and cancelling) and the resulting appointment locks. See ADR 0002 and ADR 0003 and the
 * glossary terms "Vorlaufzeit", "Buchungsfrist" and "Umbuchungs-/Stornierungsfrist".
 *
 * Boundary rules:
 * - Minutes mode: a start is allowed when start >= now + minutes (equality allowed).
 * - Fixed time mode: a day stays open while now <= deadline; afterwards the whole day is locked.
 *
 * The clock, the settings source and the date formatter can be injected for deterministic tests:
 *
 *     new Booking_policy([
 *         'now' => fn() => new DateTimeImmutable('2026-01-05 10:00:00', new DateTimeZone('UTC')),
 *         'settings' => ['booking_deadline_mode' => 'minutes', 'book_advance_timeout' => '60'],
 *         'format_date_time' => fn(DateTimeInterface $date) => $date->format('Y-m-d H:i'),
 *     ]);
 *
 * @package Libraries
 */
class Booking_policy
{
    /**
     * @var callable|null Returns the current moment (DateTimeInterface); null uses the real time.
     */
    protected $now;

    /**
     * @var array|null Fixed settings (name => value); null reads the settings table.
     */
    protected ?array $settings;

    /**
     * @var callable Formats a deadline moment for messages.
     */
    protected $format_date_time;

    /**
     * @var array Settings read during this request (name => value).
     */
    protected array $setting_cache = [];

    /**
     * Booking_policy constructor.
     *
     * @param array $params Optional "now" (callable), "settings" (array) and "format_date_time" (callable).
     */
    public function __construct(array $params = [])
    {
        $this->now = $params['now'] ?? null;
        $this->settings = $params['settings'] ?? null;
        $this->format_date_time = $params['format_date_time'] ?? 'format_date_time';
    }

    /**
     * Get the lock status of an appointment start for a new booking or for managing an existing appointment.
     *
     * @param string $start_datetime Appointment start (Y-m-d H:i:s) in the given timezone.
     * @param string $timezone Timezone of the provider.
     * @param string $kind Lock kind: "booking" (booking deadline) or "manage" (manage deadline).
     *
     * @return array Returns ['locked' => bool, 'limit_text' => string, 'message' => string]; the message contains
     * a "{$limit}" placeholder for the limit text.
     *
     * @throws Exception
     */
    public function get_lock(string $start_datetime, string $timezone, string $kind = 'booking'): array
    {
        $start = new DateTimeImmutable($start_datetime, new DateTimeZone($timezone));

        if ($kind === 'manage' && $this->setting('manage_deadline_mode', 'inherit') !== 'inherit') {
            if ($this->setting('manage_deadline_mode', 'inherit') === 'fixed_time') {
                return $this->deadline_lock(
                    $this->deadline($start->format('Y-m-d'), $timezone, 'manage_deadline_time', 'manage_deadline_days'),
                    $timezone,
                    lang('manage_locked_message_deadline')
                );
            }

            return $this->minutes_lock(
                $start,
                $timezone,
                $this->int_setting('manage_deadline_minutes', 0),
                lang('manage_locked_message_minutes')
            );
        }

        $deadline = $this->get_booking_deadline($start->format('Y-m-d'), $timezone);

        if ($deadline !== null) {
            return $this->deadline_lock($deadline, $timezone, lang('appointment_locked_message_deadline'));
        }

        return $this->minutes_lock(
            $start,
            $timezone,
            $this->int_setting('book_advance_timeout', 0),
            lang('appointment_locked_message')
        );
    }

    /**
     * Get the booking deadline of a day in fixed time mode.
     *
     * The deadline of day D is (D - deadline days) at the deadline time.
     *
     * @param string $date Day (Y-m-d).
     * @param string $timezone Timezone of the provider.
     *
     * @return DateTimeImmutable|null Returns the deadline, or null in minutes mode.
     *
     * @throws Exception
     */
    public function get_booking_deadline(string $date, string $timezone): ?DateTimeImmutable
    {
        if ($this->setting('booking_deadline_mode', 'minutes') !== 'fixed_time') {
            return null;
        }

        return $this->deadline($date, $timezone, 'booking_deadline_time', 'booking_deadline_days');
    }

    /**
     * Get the earliest moment at which a new booking may start on a day.
     *
     * @param string $date Day (Y-m-d).
     * @param string $timezone Timezone of the provider.
     *
     * @return DateTimeImmutable|null Returns the earliest allowed start (starts at this moment are allowed), or
     * null when the whole day is locked (fixed time mode after the deadline).
     *
     * @throws Exception
     */
    public function get_earliest_booking_start(string $date, string $timezone): ?DateTimeImmutable
    {
        $now = $this->now($timezone);

        $deadline = $this->get_booking_deadline($date, $timezone);

        if ($deadline !== null) {
            // Fixed time mode: no minimum lead time, only past hours are excluded.
            return $now > $deadline ? null : $now;
        }

        return $now->modify('+' . $this->int_setting('book_advance_timeout', 0) . ' minutes');
    }

    /**
     * Build the lock of a fixed time deadline.
     *
     * @param DateTimeImmutable $deadline Deadline moment.
     * @param string $timezone Timezone of the provider.
     * @param string $message Lock message.
     *
     * @return array
     *
     * @throws Exception
     */
    protected function deadline_lock(DateTimeImmutable $deadline, string $timezone, string $message): array
    {
        return [
            'locked' => $this->now($timezone) > $deadline,
            'limit_text' => ($this->format_date_time)($deadline),
            'message' => $message
        ];
    }

    /**
     * Build the lock of a minimum lead time in minutes.
     *
     * @param DateTimeImmutable $start Appointment start.
     * @param string $timezone Timezone of the provider.
     * @param int $minutes Minimum lead time in minutes.
     * @param string $message Lock message.
     *
     * @return array
     *
     * @throws Exception
     */
    protected function minutes_lock(DateTimeImmutable $start, string $timezone, int $minutes, string $message): array
    {
        $earliest_start = $this->now($timezone)->modify('+' . $minutes . ' minutes');

        return [
            'locked' => $start < $earliest_start,
            'limit_text' => sprintf('%02d:%02d', intdiv($minutes, 60), $minutes % 60),
            'message' => $message
        ];
    }

    /**
     * Calculate a fixed time deadline: (day - days setting) at the time setting.
     *
     * @param string $date Day (Y-m-d).
     * @param string $timezone Timezone of the provider.
     * @param string $time_setting Name of the time setting (HH:MM).
     * @param string $days_setting Name of the day offset setting.
     *
     * @return DateTimeImmutable
     *
     * @throws Exception
     */
    protected function deadline(
        string $date,
        string $timezone,
        string $time_setting,
        string $days_setting
    ): DateTimeImmutable {
        [$hour, $minute] = array_pad(explode(':', (string) $this->setting($time_setting, '18:00')), 2, '0');

        return (new DateTimeImmutable($date, new DateTimeZone($timezone)))
            ->setTime((int) $hour, (int) $minute, 0)
            ->modify('-' . $this->int_setting($days_setting, 1) . ' days');
    }

    /**
     * Get the current moment in a timezone.
     *
     * @param string $timezone Timezone.
     *
     * @return DateTimeImmutable
     *
     * @throws Exception
     */
    protected function now(string $timezone): DateTimeImmutable
    {
        $zone = new DateTimeZone($timezone);

        if ($this->now === null) {
            return new DateTimeImmutable('now', $zone);
        }

        return DateTimeImmutable::createFromInterface(($this->now)())->setTimezone($zone);
    }

    /**
     * Read a setting as a whole number of 0 or more.
     *
     * @param string $name Setting name.
     * @param int $default Default value.
     *
     * @return int
     */
    protected function int_setting(string $name, int $default): int
    {
        $value = $this->setting($name, $default);

        return is_numeric($value) ? max(0, (int) $value) : $default;
    }

    /**
     * Read a setting once per instance (or from the injected settings).
     *
     * @param string $name Setting name.
     * @param mixed $default Default value.
     *
     * @return mixed
     */
    protected function setting(string $name, mixed $default): mixed
    {
        if ($this->settings !== null) {
            return $this->settings[$name] ?? $default;
        }

        if (!array_key_exists($name, $this->setting_cache)) {
            $this->setting_cache[$name] = setting($name, $default);
        }

        return $this->setting_cache[$name];
    }
}
