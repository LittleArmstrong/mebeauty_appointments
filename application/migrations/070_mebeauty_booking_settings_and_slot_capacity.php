<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Mebeauty_booking_settings_and_slot_capacity extends EA_Migration
{
    /**
     * Upgrade method.
     *
     * Consolidates the former migrations 070–073: the booking deadline/buffer and
     * timezone settings, the company-header setting, the manage-deadline settings,
     * and the per-service slot capacity column.
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
            'booking_show_company_header' => '1',
            'manage_deadline_mode' => 'inherit',
            'manage_deadline_minutes' => '0',
            'manage_deadline_time' => '18:00',
            'manage_deadline_days' => '1',
        ];

        foreach ($settings as $name => $value) {
            if (!$this->db->get_where('settings', ['name' => $name])->num_rows()) {
                $this->db->insert('settings', ['name' => $name, 'value' => $value]);
            }
        }

        if (!$this->db->field_exists('slot_capacity', 'services')) {
            $this->dbforge->add_column('services', [
                'slot_capacity' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'default' => 0,
                    'after' => 'attendants_number',
                ],
            ]);
        }
    }

    /**
     * Downgrade method.
     *
     * Destructive: deletes the twelve settings and drops the services.slot_capacity
     * column, including any data stored in it.
     */
    public function down(): void
    {
        $this->db
            ->where_in('name', [
                'booking_deadline_mode',
                'booking_deadline_time',
                'booking_deadline_days',
                'appointment_buffer_time',
                'appointment_buffer_end_of_day',
                'booking_timezone_display',
                'booking_timezone_source',
                'booking_show_company_header',
                'manage_deadline_mode',
                'manage_deadline_minutes',
                'manage_deadline_time',
                'manage_deadline_days',
            ])
            ->delete('settings');

        if ($this->db->field_exists('slot_capacity', 'services')) {
            $this->dbforge->drop_column('services', 'slot_capacity');
        }
    }
}
