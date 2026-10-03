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

class Migration_Revert_mebeauty_default_theme extends EA_Migration
{
    /**
     * Upgrade method.
     */
    public function up(): void
    {
        // Restore the generic default theme; mebeauty remains selectable.
        $this->db
            ->where('name', 'theme')
            ->where('value', 'mebeauty')
            ->update('settings', ['value' => 'default']);
    }

    /**
     * Downgrade method.
     */
    public function down(): void
    {
        $this->db
            ->where('name', 'theme')
            ->where('value', 'default')
            ->update('settings', ['value' => 'mebeauty']);
    }
}
