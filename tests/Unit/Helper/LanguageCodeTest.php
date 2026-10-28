<?php

namespace Tests\Unit\Helper;

use Tests\TestCase;

class LanguageCodeTest extends TestCase
{
    private ?string $previous_language = null;

    protected function setUp(): void
    {
        $this->previous_language = config('language');
    }

    protected function tearDown(): void
    {
        config(['language' => $this->previous_language]);
    }

    public function test_known_language_names_map_to_their_code(): void
    {
        $this->assertSame('de', language_code('german'));
        $this->assertSame('en', language_code('english'));
        $this->assertSame('pt-br', language_code('portuguese-br'));
    }

    public function test_unknown_or_empty_language_falls_back_to_english(): void
    {
        $this->assertSame('en', language_code('klingon'));
        $this->assertSame('en', language_code(''));
    }

    public function test_without_argument_the_current_rendering_language_is_used(): void
    {
        // Notifications switch config('language') to the recipient's language before rendering an email.
        config(['language' => 'german']);

        $this->assertSame('de', language_code());
    }

    public function test_email_templates_declare_the_rendering_language(): void
    {
        $templates = glob(APPPATH . 'views/emails/*_email.php');

        $this->assertNotEmpty($templates);

        foreach ($templates as $template) {
            $declares_rendering_language = str_contains(
                file_get_contents($template),
                '<html lang="<?= e(language_code()) ?>">'
            );

            $this->assertTrue(
                $declares_rendering_language,
                basename($template) . ' must declare the rendering language'
            );
        }
    }
}
