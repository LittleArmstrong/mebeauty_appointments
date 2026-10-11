<?php

namespace Tests\Unit\Helper;

use Tests\TestCase;

class HtmlHelperTest extends TestCase
{
    public function testPureHtmlStripsJavaScript(): void
    {
        $this->assertSame('<p>Hello</p>', pure_html('<script>alert(1)</script><p>Hello</p>'));
    }

    public function testPureHtmlWritesSerializerCacheToConfiguredPath(): void
    {
        $originalCachePath = config('cache_path');
        $tmp = sys_get_temp_dir() . '/htmlpurifier-cache-' . bin2hex(random_bytes(4));

        config(['cache_path' => $tmp . '/']);

        try {
            pure_html('<p style="color:red">Hello</p>');

            $htmlDir = $tmp . '/htmlpurifier/HTML';
            $cssDir = $tmp . '/htmlpurifier/CSS';

            $this->assertDirectoryExists($htmlDir);
            $this->assertDirectoryExists($cssDir);

            $htmlFiles = glob($htmlDir . '/*.ser');
            $cssFiles = glob($cssDir . '/*.ser');

            $this->assertNotFalse($htmlFiles);
            $this->assertNotEmpty($htmlFiles);
            $this->assertNotFalse($cssFiles);
            $this->assertNotEmpty($cssFiles);

            pure_html('<p style="color:red">Hello</p>');

            $this->assertCount(count($htmlFiles), glob($htmlDir . '/*.ser'));
            $this->assertCount(count($cssFiles), glob($cssDir . '/*.ser'));
        } finally {
            config(['cache_path' => $originalCachePath]);
            $this->removeDirectory($tmp);
        }
    }

    public function testPureHtmlFallsBackGracefullyWhenCacheDirectoryCannotBeCreated(): void
    {
        $originalCachePath = config('cache_path');
        $blocker = sys_get_temp_dir() . '/hp-cache-blocker-' . bin2hex(random_bytes(4));

        file_put_contents($blocker, 'block');

        config(['cache_path' => $blocker . '/']);

        try {
            $this->assertSame('<p>Hello</p>', pure_html('<script>alert(1)</script><p>Hello</p>'));
        } finally {
            config(['cache_path' => $originalCachePath]);
            @unlink($blocker);
        }
    }

    private function removeDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        foreach (scandir($dir) ?: [] as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $dir . DIRECTORY_SEPARATOR . $item;

            is_dir($path) ? $this->removeDirectory($path) : @unlink($path);
        }

        @rmdir($dir);
    }
}
