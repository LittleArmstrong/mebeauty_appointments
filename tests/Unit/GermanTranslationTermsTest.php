<?php

namespace Tests\Unit;

use Tests\TestCase;

/**
 * Guards a subset of docs/translation-style-de.md in german/translations_lang.php: the words "Anbieter",
 * "Dienstleister", "Sekretär", "Bearbeiter" and "Service(s)" and the informal address ("du", "dich", "dir",
 * "dein…") must not appear. The other "Avoid" entries of the style guide (e.g. "Klient", "reservieren",
 * "Nichtverfügbarkeit") are not checked here.
 */
class GermanTranslationTermsTest extends TestCase
{
    private const AVOIDED = [
        'Anbieter' => '/\bAnbieter/u',
        'Dienstleister' => '/\bDienstleister/u',
        'Sekretär' => '/Sekretär/u',
        'Bearbeiter' => '/\bBearbeiter/u',
        'Service' => '/\bServices?\b/u',
        'du/dich/dir/dein' => '/\b(du|dich|dir|dein\w*)\b/iu'
    ];

    public function test_german_texts_avoid_the_terms_of_the_style_guide(): void
    {
        $lang = [];

        include APPPATH . 'language/german/translations_lang.php';

        $violations = [];

        foreach ($lang as $key => $text) {
            foreach (self::AVOIDED as $term => $pattern) {
                if (preg_match($pattern, $text)) {
                    $violations[] = $key . ' (' . $term . ')';
                }
            }
        }

        $this->assertSame(
            [],
            $violations,
            'Avoided terms in german/translations_lang.php: ' . implode(', ', $violations)
        );
    }
}
