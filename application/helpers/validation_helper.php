<?php defined('BASEPATH') or exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 * Easy!Appointments - Online Appointment Scheduler
 *
 * @package     EasyAppointments
 * @author      A.Tselegidis <alextselegidis@gmail.com>
 * @copyright   Copyright (c) Alex Tselegidis
 * @license     https://opensource.org/licenses/GPL-3.0 - GPLv3
 * @link        https://easyappointments.org
 * @since       v1.0.0
 * ---------------------------------------------------------------------------- */

/**
 * Validate a date time value.
 *
 * @param string $value Validation value.
 *
 * @return bool Returns the validation result.
 */
function validate_datetime(string $value): bool
{
    $date_time = DateTime::createFromFormat('Y-m-d H:i:s', $value);

    return (bool) $date_time;
}

/**
 * Validate the value of a setting that has a server-side rule (settings introduced by the MeBeauty fork and the
 * company color, see .scratch/0010-input-hardening).
 *
 * Settings without a rule are not checked and always pass.
 *
 * @param string $name Setting name.
 * @param mixed $value Setting value.
 *
 * @return string|null Returns an error message, or null when the value is valid.
 */
function validate_setting_value(string $name, mixed $value): ?string
{
    $time = ['booking_deadline_time', 'manage_deadline_time'];

    $non_negative_integer = [
        'booking_deadline_days',
        'manage_deadline_days',
        'manage_deadline_minutes',
        'appointment_buffer_time',
    ];

    $enum = [
        'booking_deadline_mode' => ['minutes', 'fixed_time'],
        'manage_deadline_mode' => ['inherit', 'minutes', 'fixed_time'],
        'booking_timezone_display' => ['dropdown', 'confirm_only', 'hidden'],
        'booking_timezone_source' => ['company', 'browser'],
        'appointment_buffer_end_of_day' => ['0', '1'],
        'booking_show_company_header' => ['0', '1'],
    ];

    $string = is_scalar($value) ? (string) $value : null;

    if (in_array($name, $time, true)) {
        return $string !== null && preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $string)
            ? null
            : sprintf('Invalid value for setting "%s": expected a time HH:MM between 00:00 and 23:59.', $name);
    }

    if (in_array($name, $non_negative_integer, true)) {
        return $string !== null && ctype_digit($string)
            ? null
            : sprintf('Invalid value for setting "%s": expected a whole number of 0 or more.', $name);
    }

    if (isset($enum[$name])) {
        return $string !== null && in_array($string, $enum[$name], true)
            ? null
            : sprintf('Invalid value for setting "%s": expected one of %s.', $name, implode(', ', $enum[$name]));
    }

    if ($name === 'company_color') {
        return $string !== null && preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $string)
            ? null
            : sprintf('Invalid value for setting "%s": expected a hex color (#RGB or #RRGGBB).', $name);
    }

    return null;
}
