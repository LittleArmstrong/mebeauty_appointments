<?php

/*
 * Deterministic validator for static asset references under assets/img.
 *
 * Scans application/ and docs/ recursively for literal references of the form
 * assets/img/<name>.<ext> and checks that each referenced file exists. External
 * URLs, dynamically assembled paths and template variables are skipped, because
 * they either carry no assets/img/ prefix or no clean image extension.
 *
 * Run directly:
 *     php scripts/validate-asset-references.php [repo-root]
 * Exits 0 when valid, 1 otherwise. When included (e.g. from PHPUnit), only the
 * validate_asset_references() function is defined and nothing is executed.
 */

/**
 * Validate static asset references.
 *
 * @param string $root Repository root directory.
 *
 * @return array List of error messages (empty means valid).
 */
function validate_asset_references(string $root): array
{
    $errors = [];

    $scanned_directories = ['application', 'docs'];

    $pattern = '#(?<![\/:])assets/img/[a-zA-Z0-9_./-]+\.(?:png|jpe?g|webp|ico|svg|gif|avif)#';

    $root = rtrim($root, '/');

    foreach ($scanned_directories as $subdir) {
        $directory = $root . '/' . $subdir;

        if (!is_dir($directory)) {
            continue;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS),
        );

        foreach ($iterator as $file) {
            if (!$file->isFile()) {
                continue;
            }

            $extension = strtolower($file->getExtension());

            if ($extension !== 'php' && $extension !== 'md') {
                continue;
            }

            $relative_file = substr($file->getPathname(), strlen($root) + 1);

            $content = (string) file_get_contents($file->getPathname());

            if (preg_match_all($pattern, $content, $matches) === false) {
                continue;
            }

            foreach (array_unique($matches[0]) as $reference) {
                if (!file_exists($root . '/' . $reference)) {
                    $errors[] = sprintf('%s: %s (Datei nicht gefunden)', $relative_file, $reference);
                }
            }
        }
    }

    return $errors;
}

// Only run the CLI entry point when invoked directly, not when included by a test.
if (PHP_SAPI === 'cli' && isset($argv) && basename($argv[0]) === basename(__FILE__)) {
    $root = $argv[1] ?? getcwd();

    $errors = validate_asset_references($root);

    if ($errors !== []) {
        fwrite(STDERR, implode(PHP_EOL, $errors) . PHP_EOL);
        exit(1);
    }

    echo 'OK: Alle Asset-Referenzen in application/ und docs/ sind auflösbar.' . PHP_EOL;
    exit(0);
}
