<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Mebeauty_booking_settings_and_slot_capacity extends EA_Migration
{
    /**
     * Upgrade method.
     *
     * Collects all fork changes until the first production release (ADR 0005): the
     * booking deadline/buffer and timezone settings, the company-header setting, the
     * manage-deadline settings, the language-selector setting (formerly 071), the
     * per-service slot capacity column, the appointment_contacts table (spec 0017,
     * ADR 0008) and the email verification setting, the appointment_verifications table
     * and the email_verification_limits table (spec 0018). Idempotent: only creates what is missing, so
     * an existing database can be re-migrated from version 69.
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
            'display_language_selector' => '1',
            'require_email_verification' => '0'
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
                    'after' => 'attendants_number'
                ]
            ]);
        }

        if (!$this->db->table_exists('appointment_contacts')) {
            $this->dbforge->add_field([
                'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
                'id_appointments' => ['type' => 'INT', 'constraint' => 11, 'null' => false],
                'first_name' => ['type' => 'VARCHAR', 'constraint' => 256, 'null' => true],
                'last_name' => ['type' => 'VARCHAR', 'constraint' => 512, 'null' => true],
                'email' => ['type' => 'VARCHAR', 'constraint' => 512, 'null' => true],
                'phone_number' => ['type' => 'VARCHAR', 'constraint' => 128, 'null' => true],
                'create_datetime' => ['type' => 'DATETIME', 'null' => true],
                'update_datetime' => ['type' => 'DATETIME', 'null' => true]
            ]);

            $this->dbforge->add_key('id', true);

            $this->dbforge->create_table('appointment_contacts', true, ['engine' => 'InnoDB']);

            $this->db->query(
                'ALTER TABLE `' .
                    $this->db->dbprefix('appointment_contacts') .
                    '`
                    ADD UNIQUE KEY `appointment_contacts_appointment` (`id_appointments`),
                    ADD CONSTRAINT `appointment_contacts_appointments` FOREIGN KEY (`id_appointments`) REFERENCES `' .
                    $this->db->dbprefix('appointments') .
                    '` (`id`)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE'
            );
        }

        // Marks an appointment as Vormerkung (purpose "booking") or holds a changed email (purpose "email_change")
        // until the link is clicked. Only the token hash is stored. The restore data (service, provider, start, end,
        // notes, customer form data) outlives the appointment of an expired Vormerkung, hence ON DELETE SET NULL;
        // "restorable" is set only by the expiry cleanup, so a Vormerkung deleted any other way stays deleted.
        if (!$this->db->table_exists('appointment_verifications')) {
            $this->dbforge->add_field([
                'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
                'id_appointments' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
                'purpose' => ['type' => 'VARCHAR', 'constraint' => 32, 'null' => false],
                'email' => ['type' => 'VARCHAR', 'constraint' => 512, 'null' => false],
                'token_hash' => ['type' => 'CHAR', 'constraint' => 64, 'null' => false],
                'expires_datetime' => ['type' => 'DATETIME', 'null' => false],
                'last_sent_datetime' => ['type' => 'DATETIME', 'null' => true],
                'send_count' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
                'restorable' => ['type' => 'TINYINT', 'constraint' => 1, 'null' => false, 'default' => 0],
                'id_services' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
                'id_users_provider' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
                'start_datetime' => ['type' => 'DATETIME', 'null' => true],
                'end_datetime' => ['type' => 'DATETIME', 'null' => true],
                'notes' => ['type' => 'TEXT', 'null' => true],
                'customer_data' => ['type' => 'TEXT', 'null' => true],
                'create_datetime' => ['type' => 'DATETIME', 'null' => true],
                'update_datetime' => ['type' => 'DATETIME', 'null' => true]
            ]);

            $this->dbforge->add_key('id', true);
            $this->dbforge->add_key('email');

            $this->dbforge->create_table('appointment_verifications', true, ['engine' => 'InnoDB']);

            $this->db->query(
                'ALTER TABLE `' .
                    $this->db->dbprefix('appointment_verifications') .
                    '`
                    ADD UNIQUE KEY `appointment_verifications_token_hash` (`token_hash`),
                    ADD KEY `appointment_verifications_appointment` (`id_appointments`),
                    ADD CONSTRAINT `appointment_verifications_appointments` FOREIGN KEY (`id_appointments`) REFERENCES `' .
                    $this->db->dbprefix('appointments') .
                    '` (`id`)
                    ON DELETE SET NULL
                    ON UPDATE CASCADE'
            );
        }

        // Send limit of the verification mails per email (spec 0018/03): the window of at most 5 mails per hour and
        // the last mail (60 seconds until a resend). Kept apart from the verifications, so it counts across
        // Vormerkungen and survives a confirmed or deleted one.
        if (!$this->db->table_exists('email_verification_limits')) {
            $this->dbforge->add_field([
                'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
                'email' => ['type' => 'VARCHAR', 'constraint' => 512, 'null' => false],
                'window_start_datetime' => ['type' => 'DATETIME', 'null' => false],
                'window_count' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
                'last_sent_datetime' => ['type' => 'DATETIME', 'null' => false]
            ]);

            $this->dbforge->add_key('id', true);
            $this->dbforge->add_key('last_sent_datetime');

            $this->dbforge->create_table('email_verification_limits', true, ['engine' => 'InnoDB']);

            $this->db->query(
                'ALTER TABLE `' .
                    $this->db->dbprefix('email_verification_limits') .
                    '` ADD UNIQUE KEY `email_verification_limits_email` (`email`)'
            );
        }
    }

    /**
     * Downgrade method.
     *
     * Destructive: deletes the settings, drops the services.slot_capacity column and
     * the appointment_contacts, appointment_verifications and email_verification_limits tables, including any data
     * stored in them.
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
                'display_language_selector',
                'require_email_verification'
            ])
            ->delete('settings');

        if ($this->db->field_exists('slot_capacity', 'services')) {
            $this->dbforge->drop_column('services', 'slot_capacity');
        }

        if ($this->db->table_exists('email_verification_limits')) {
            $this->dbforge->drop_table('email_verification_limits');
        }

        if ($this->db->table_exists('appointment_verifications')) {
            $this->dbforge->drop_table('appointment_verifications');
        }

        if ($this->db->table_exists('appointment_contacts')) {
            $this->dbforge->drop_table('appointment_contacts');
        }
    }
}
