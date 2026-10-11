<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ViewEscapingValidatorTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        require_once __DIR__ . '/../../scripts/validate-view-escaping.php';
    }

    public function testVariableOutputIsReportedWithLineNumber(): void
    {
        $content = "<div>\n    <a href=\"<?= \$site_url ?>/\">Home</a>\n</div>\n";

        $this->assertSame([['line' => 2, 'output' => '<?= $site_url ?>']], \find_unescaped_view_output($content));
    }

    public function testSettingAndViewVariableHelpersAreReported(): void
    {
        $content = "<?= setting('company_name') ?>\n<?= vars('page_title') ?>\n<?= html_vars('page_title') ?>\n";

        $this->assertSame([1, 2, 3], array_column(\find_unescaped_view_output($content), 'line'));
    }

    public function testEscapedAndTrustedOutputIsNotReported(): void
    {
        $content =
            "<?= e(\$site_url) ?>\n<?=e(setting('company_name'))?>\n<?= lang('site_nav_home') ?>\n<?= asset_url('assets/img/x.png') ?>\n";

        $this->assertSame([], \find_unescaped_view_output($content));
    }

    public function testOutputWithoutSpacesIsReported(): void
    {
        $this->assertSame(
            [['line' => 1, 'output' => '<?=$whatsapp_url?>']],
            \find_unescaped_view_output('<?=$whatsapp_url?>')
        );
    }
}
