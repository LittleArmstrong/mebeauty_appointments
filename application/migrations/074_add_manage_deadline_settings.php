<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_manage_deadline_settings extends EA_Migration
{
    /**
     * Upgrade method.
     */
    public function up(): void
    {
        $settings = [
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
    }

    /**
     * Downgrade method.
     */
    public function down(): void
    {
        $this->db
            ->where_in('name', [
                'manage_deadline_mode',
                'manage_deadline_minutes',
                'manage_deadline_time',
                'manage_deadline_days',
            ])
            ->delete('settings');
    }
}
