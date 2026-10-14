<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class BookingFooterRulesTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        if (!class_exists('Booking_footer_rules')) {
            require_once APPPATH . 'libraries/Booking_footer_rules.php';
        }
    }

    private function rules(): \Booking_footer_rules
    {
        return new \Booking_footer_rules();
    }

    private const EMPTY_FOOTER = [
        'display_login_button' => false,
        'display_language_selector' => false,
        'legal_notice_url' => '',
        'imprint_url' => ''
    ];

    public function test_empty_footer_is_not_shown(): void
    {
        $this->assertFalse($this->rules()->is_shown('1', self::EMPTY_FOOTER));
    }

    public function test_footer_with_null_values_is_not_shown(): void
    {
        $this->assertFalse(
            $this->rules()->is_shown('1', [
                'display_login_button' => null,
                'display_language_selector' => false,
                'legal_notice_url' => null,
                'imprint_url' => null
            ])
        );
    }

    public static function single_element_provider(): array
    {
        return [
            'login button' => ['display_login_button', true],
            'language selector' => ['display_language_selector', true],
            'legal notice url' => ['legal_notice_url', 'https://example.com/legal'],
            'imprint url' => ['imprint_url', 'https://example.com/imprint']
        ];
    }

    #[DataProvider('single_element_provider')]
    public function test_footer_with_one_element_is_shown(string $field, mixed $value): void
    {
        $this->assertTrue($this->rules()->is_shown('1', [$field => $value] + self::EMPTY_FOOTER));
    }

    #[DataProvider('single_element_provider')]
    public function test_disabled_setting_hides_footer_with_content(string $field, mixed $value): void
    {
        $this->assertFalse($this->rules()->is_shown('0', [$field => $value] + self::EMPTY_FOOTER));
    }
}
