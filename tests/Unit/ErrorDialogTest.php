<?php

namespace Tests\Unit;

use Tests\TestCase;

class ErrorDialogTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        if (!class_exists('Error_dialog')) {
            require_once APPPATH . 'libraries/Error_dialog.php';
        }
    }

    public function test_title_is_the_company_name(): void
    {
        $this->assertSame('Studio Lena', (new \Error_dialog())->title_for('Studio Lena'));
    }

    public function test_title_is_html_escaped(): void
    {
        $this->assertSame('A &amp; B &lt;i&gt;', (new \Error_dialog())->title_for('A & B <i>'));
    }

    public function test_missing_company_name_falls_back_to_the_default(): void
    {
        $this->assertSame(e(DEFAULT_COMPANY_NAME), (new \Error_dialog())->title_for(null));
        $this->assertSame(e(DEFAULT_COMPANY_NAME), (new \Error_dialog())->title_for(''));
    }
}
