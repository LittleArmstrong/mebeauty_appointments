<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_display_language_selector_setting extends EA_Migration
{
    /**
     * Upgrade method.
     *
     * Adds the setting that shows or hides the language selector of the booking flow (default: shown, as in
     * Easy!Appointments; see .scratch/0014-theme-site-chrome).
     */
    public function up(): void
    {
        if (!$this->db->get_where('settings', ['name' => 'display_language_selector'])->num_rows()) {
            $this->db->insert('settings', ['name' => 'display_language_selector', 'value' => '1']);
        }
    }

    /**
     * Downgrade method.
     */
    public function down(): void
    {
        $this->db->delete('settings', ['name' => 'display_language_selector']);
    }
}
