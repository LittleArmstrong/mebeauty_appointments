<?php

namespace Tests\Unit;

use Tests\TestCase;

class AssetCacheBustingTest extends TestCase
{
    private string $root;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        if (!class_exists('Asset_cache_busting')) {
            require_once APPPATH . 'libraries/Asset_cache_busting.php';
        }
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->root = sys_get_temp_dir() . '/asset-cache-busting-' . bin2hex(random_bytes(4));

        mkdir($this->root . '/assets/js', 0777, true);
    }

    protected function tearDown(): void
    {
        foreach (glob($this->root . '/assets/js/*') as $file) {
            unlink($file);
        }

        rmdir($this->root . '/assets/js');
        rmdir($this->root . '/assets');
        rmdir($this->root);

        parent::tearDown();
    }

    private function write(string $uri, int $mtime): void
    {
        file_put_contents($this->root . '/' . $uri, 'x');
        touch($this->root . '/' . $uri, $mtime);
    }

    private function token(string $uri): string
    {
        return (new \Asset_cache_busting())->token($this->root, $uri, 'TSJ83');
    }

    public function test_existing_file_gives_its_modification_time(): void
    {
        $this->write('assets/js/app.js', 1760000000);

        $this->assertSame('1760000000', $this->token('assets/js/app.js'));
    }

    public function test_missing_file_gives_the_configured_token(): void
    {
        $this->assertSame('TSJ83', $this->token('assets/js/missing.js'));
    }

    public function test_minified_path_resolves_to_the_minified_file(): void
    {
        $this->write('assets/js/app.js', 1760000000);
        $this->write('assets/js/app.min.js', 1760000500);

        $this->assertSame('1760000500', $this->token('assets/js/app.min.js'));
    }

    public function test_non_minified_path_resolves_to_the_non_minified_file(): void
    {
        $this->write('assets/js/app.js', 1760000000);
        $this->write('assets/js/app.min.js', 1760000500);

        $this->assertSame('1760000000', $this->token('assets/js/app.js'));
    }

    public function test_production_serves_the_minified_file_and_its_token(): void
    {
        $this->write('assets/js/app.js', 1760000000);
        $this->write('assets/js/app.min.js', 1760000500);

        $served = (new \Asset_cache_busting())->served_uri('assets/js/app.js', false);

        $this->assertSame('assets/js/app.min.js', $served);
        $this->assertSame('1760000500', $this->token($served));
    }

    public function test_debug_mode_serves_the_source_file_and_its_token(): void
    {
        $this->write('assets/js/app.js', 1760000000);
        $this->write('assets/js/app.min.js', 1760000500);

        $served = (new \Asset_cache_busting())->served_uri('assets/js/app.js', true);

        $this->assertSame('assets/js/app.js', $served);
        $this->assertSame('1760000000', $this->token($served));
    }

    public function test_production_keeps_an_already_minified_uri(): void
    {
        $this->assertSame(
            'assets/css/app.min.css',
            (new \Asset_cache_busting())->served_uri('assets/css/app.min.css', false)
        );
    }

    public function test_directory_gives_the_configured_token(): void
    {
        $this->assertSame('TSJ83', $this->token('assets/js'));
    }
}
