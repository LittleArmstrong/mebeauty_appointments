<?php

namespace Tests\Unit\Helper;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SettingValidationTest extends TestCase
{
    public static function validValues(): array
    {
        return [
            ['booking_deadline_time', '00:00'],
            ['booking_deadline_time', '23:59'],
            ['manage_deadline_time', '18:00'],
            ['booking_deadline_days', '0'],
            ['manage_deadline_days', 3],
            ['manage_deadline_minutes', '1440'],
            ['appointment_buffer_time', '0'],
            ['booking_deadline_mode', 'fixed_time'],
            ['manage_deadline_mode', 'inherit'],
            ['booking_timezone_display', 'confirm_only'],
            ['booking_timezone_source', 'browser'],
            ['appointment_buffer_end_of_day', '0'],
            ['booking_show_company_header', 1],
            ['company_color', '#fff'],
            ['company_color', '#d1ae5e'],
            ['company_color', '#ffffff'],
            ['company_name', '<anything goes>']
        ];
    }

    public static function invalidValues(): array
    {
        return [
            ['booking_deadline_time', '24:00'],
            ['booking_deadline_time', '9:5'],
            ['manage_deadline_time', ''],
            ['booking_deadline_days', '-1'],
            ['manage_deadline_minutes', 'abc'],
            ['appointment_buffer_time', '1.5'],
            ['booking_deadline_mode', 'both'],
            ['manage_deadline_mode', 'minutes '],
            ['booking_timezone_display', 'none'],
            ['appointment_buffer_end_of_day', 'yes'],
            ['company_color', 'red;}'],
            ['company_color', '#12345'],
            ['booking_deadline_days', ['1']]
        ];
    }

    #[DataProvider('validValues')]
    public function testValidValuesPass(string $name, mixed $value): void
    {
        $this->assertNull(validate_setting_value($name, $value));
    }

    #[DataProvider('invalidValues')]
    public function testInvalidValuesAreRejectedWithSettingName(string $name, mixed $value): void
    {
        $error = validate_setting_value($name, $value);

        $this->assertNotNull($error);
        $this->assertStringContainsString('"' . $name . '"', $error);
    }

    public function testValidateValuesRejectsTheWholeBatchWhenOneValueIsInvalid(): void
    {
        $CI = &get_instance();
        $CI->load->model('settings_model');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('"manage_deadline_days"');

        $CI->settings_model->validate_values([
            ['name' => 'booking_deadline_time', 'value' => '18:00'],
            ['name' => 'manage_deadline_days', 'value' => '-2']
        ]);
    }

    public function testMigrationDefaultsPassTheRules(): void
    {
        $migration = file_get_contents(
            __DIR__ . '/../../../application/migrations/070_mebeauty_booking_settings_and_slot_capacity.php'
        );

        preg_match_all("/^\s*'([a-z_]+)' => '([^']*)',?$/m", $migration, $matches, PREG_SET_ORDER);

        $this->assertNotEmpty($matches);

        foreach ($matches as [, $name, $value]) {
            $this->assertNull(validate_setting_value($name, $value), "Default of $name is invalid");
        }
    }
}
