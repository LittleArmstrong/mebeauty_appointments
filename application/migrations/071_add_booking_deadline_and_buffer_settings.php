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
