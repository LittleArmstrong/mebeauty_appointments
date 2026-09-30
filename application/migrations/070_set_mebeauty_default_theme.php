<?php defined('BASEPATH') or exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 * Easy!Appointments - Online Appointment Scheduler
 *
 * @package     EasyAppointments
 * @author      A.Tselegidis <alextselegidis@gmail.com>
 * @copyright   Copyright (c) Alex Tselegidis
 * @license     https://opensource.org/licenses/GPL-3.0 - GPLv3
 * @link        https://easyappointments.org
 * @since       v1.5.0
 * ---------------------------------------------------------------------------- */

class Migration_Set_mebeauty_default_theme extends EA_Migration
{
    /**
     * Upgrade method.
     */
    public function up(): void
    {
        // Switch the default theme to mebeauty, but keep explicit non-default choices untouched.
        $this->db
            ->where('name', 'theme')
            ->where('value', 'default')
            ->update('settings', ['value' => 'mebeauty']);
    }

    /**
     * Downgrade method.
     */
    public function down(): void
    {
        $this->db
            ->where('name', 'theme')
            ->where('value', 'mebeauty')
            ->update('settings', ['value' => 'default']);
    }
}
