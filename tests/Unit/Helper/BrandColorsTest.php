<?php

namespace Tests\Unit\Helper;

use Tests\TestCase;

class BrandColorsTest extends TestCase
{
    private const FALLBACK = ['primary' => '#35A768', 'hover' => '#34495e'];

    public function testValidCompanyColorWins(): void
    {
        $this->assertSame(
            ['primary' => '#123456', 'hover' => '#123456'],
            brand_colors('#123456', 'mebeauty', self::FALLBACK)
        );
    }

    public function testWhiteMeansNoCompanyColorAndTheThemeColorIsUsed(): void
    {
        $this->assertSame(
            ['primary' => '#d1ae5e', 'hover' => '#b8943f'],
            brand_colors('#ffffff', 'mebeauty', self::FALLBACK)
        );
        $this->assertSame(['primary' => '#d1ae5e', 'hover' => '#b8943f'], brand_colors('', 'mebeauty', self::FALLBACK));
    }

    public function testInvalidCompanyColorIsIgnored(): void
    {
        $this->assertSame('#d1ae5e', brand_colors('red;}', 'mebeauty', self::FALLBACK)['primary']);
    }

    public function testThemeWithoutDeclaredColorUsesTheFallback(): void
    {
        $this->assertSame(self::FALLBACK, brand_colors(null, 'default', self::FALLBACK));
        $this->assertSame(self::FALLBACK, brand_colors(null, '../../config', self::FALLBACK));
    }

    public function testThemeConfigOfMebeauty(): void
    {
        $this->assertSame('#d1ae5e', theme_config('mebeauty')['primary_color']);
        $this->assertSame([], theme_config('default'));
    }
}
