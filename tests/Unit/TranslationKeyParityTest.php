<?php

namespace Tests\Unit;

use Tests\TestCase;

/**
 * German and English are the two maintained languages of the fork: every key (including fork-own keys) must exist
 * in both, so a new key cannot silently fall back or go missing in one of them.
 */
class TranslationKeyParityTest extends TestCase
{
    private static function keys(string $language): array
    {
        $lang = [];

        include APPPATH . 'language/' . $language . '/translations_lang.php';

        return array_keys($lang);
    }

    public function test_german_defines_every_english_key(): void
    {
        $missing = array_values(array_diff(self::keys('english'), self::keys('german')));

        $this->assertSame([], $missing, 'Keys missing in german/translations_lang.php: ' . implode(', ', $missing));
    }

    public function test_english_defines_every_german_key(): void
    {
        $missing = array_values(array_diff(self::keys('german'), self::keys('english')));

        $this->assertSame([], $missing, 'Keys missing in english/translations_lang.php: ' . implode(', ', $missing));
    }
}
