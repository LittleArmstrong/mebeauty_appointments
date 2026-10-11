<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class MigrationValidatorTest extends TestCase
{
    private string $tmpDir;

    public static function setUpBeforeClass(): void
    {
        require_once __DIR__ . '/../../scripts/validate-migrations.php';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->tmpDir = sys_get_temp_dir() . '/migration-validator-' . bin2hex(random_bytes(4));

        mkdir($this->tmpDir);
    }

    protected function tearDown(): void
    {
        $this->removeDir($this->tmpDir);

        parent::tearDown();
    }

    public function testValidHistory(): void
    {
        $this->writeMigration('001_first.php', 'Migration_First');
        $this->writeMigration('002_second.php', 'Migration_Second');

        $this->assertSame([], \validate_migrations($this->tmpDir));
    }

    public function testDuplicateNumber(): void
    {
        $this->writeMigration('001_first.php', 'Migration_First');
        $this->writeMigration('001_second.php', 'Migration_Second');

        $errors = \validate_migrations($this->tmpDir);

        $this->assertNotEmpty($errors);
        $this->assertStringContainsString('doppelte Nummer 001', implode("\n", $errors));
    }

    public function testMissingNumber(): void
    {
        $this->writeMigration('001_first.php', 'Migration_First');
        $this->writeMigration('003_third.php', 'Migration_Third');

        $errors = \validate_migrations($this->tmpDir);

        $this->assertNotEmpty($errors);
        $this->assertStringContainsString('fehlende Nummer 002', implode("\n", $errors));
    }

    public function testInvalidFilenamePrefix(): void
    {
        $this->writeMigration('abc_foo.php', 'Migration_Foo');

        $errors = \validate_migrations($this->tmpDir);

        $this->assertNotEmpty($errors);
        $this->assertStringContainsString('ungültiges Dateinamenpräfix', implode("\n", $errors));
    }

    public function testWrongClassName(): void
    {
        $this->writeMigration('001_first.php', 'Migration_WrongName');

        $errors = \validate_migrations($this->tmpDir);

        $this->assertNotEmpty($errors);
        $this->assertStringContainsString(
            'Klasse Migration_WrongName — erwartet Migration_First',
            implode("\n", $errors)
        );
    }

    public function testMissingClass(): void
    {
        file_put_contents($this->tmpDir . '/001_first.php', "<?php\n\n// keine Klasse\n");

        $errors = \validate_migrations($this->tmpDir);

        $this->assertNotEmpty($errors);
        $this->assertStringContainsString('keine Klasse deklariert', implode("\n", $errors));
    }

    private function writeMigration(string $name, string $class): void
    {
        file_put_contents(
            $this->tmpDir . '/' . $name,
            "<?php\n\nclass $class extends EA_Migration {\n    public function up(): void {}\n    public function down(): void {}\n}\n"
        );
    }

    private function removeDir(string $dir): void
    {
        foreach ((array) glob($dir . '/*') as $file) {
            is_dir($file) ? $this->removeDir($file) : unlink($file);
        }

        rmdir($dir);
    }
}
