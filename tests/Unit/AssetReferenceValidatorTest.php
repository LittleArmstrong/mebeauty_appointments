<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class AssetReferenceValidatorTest extends TestCase {
    private string $tmpRoot;

    public static function setUpBeforeClass(): void
    {
        require_once __DIR__ . '/../../scripts/validate-asset-references.php';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->tmpRoot = sys_get_temp_dir() . '/asset-validator-' . bin2hex(random_bytes(4));

        mkdir($this->tmpRoot . '/assets/img', 0777, true);
        mkdir($this->tmpRoot . '/application/views', 0777, true);
        mkdir($this->tmpRoot . '/docs', 0777, true);

        file_put_contents($this->tmpRoot . '/assets/img/existing.png', 'x');
    }

    protected function tearDown(): void
    {
        $this->removeDir($this->tmpRoot);

        parent::tearDown();
    }

    public function testExistingReferenceProducesNoError(): void
    {
        $this->writeView("<?php echo asset_url('assets/img/existing.png');");

        $this->assertSame([], \validate_asset_references($this->tmpRoot));
    }

    public function testMissingReferenceProducesError(): void
    {
        $this->writeView("<?php echo base_url('assets/img/missing.png');");

        $errors = \validate_asset_references($this->tmpRoot);

        $this->assertNotEmpty($errors);
        $this->assertStringContainsString('assets/img/missing.png', implode("\n", $errors));
        $this->assertStringContainsString('application/views/sample.php', implode("\n", $errors));
    }

    public function testExternalUrlIsSkipped(): void
    {
        $this->writeView("<?php echo 'https://example.com/assets/img/remote.png';");

        $this->assertSame([], \validate_asset_references($this->tmpRoot));
    }

    public function testDynamicPathIsSkipped(): void
    {
        $this->writeView("<?php echo asset_url('assets/img/' . \$logo . '.png');");

        $this->assertSame([], \validate_asset_references($this->tmpRoot));
    }

    public function testMarkdownReferenceIsChecked(): void
    {
        file_put_contents($this->tmpRoot . '/docs/sample.md', 'siehe `assets/img/missing.png`');

        $errors = \validate_asset_references($this->tmpRoot);

        $this->assertNotEmpty($errors);
        $this->assertStringContainsString('docs/sample.md', implode("\n", $errors));
    }

    private function writeView(string $content): void
    {
        file_put_contents($this->tmpRoot . '/application/views/sample.php', $content);
    }

    private function removeDir(string $dir): void
    {
        foreach ((array) glob($dir . '/*') as $file) {
            is_dir($file) ? $this->removeDir($file) : unlink($file);
        }

        rmdir($dir);
    }
}
