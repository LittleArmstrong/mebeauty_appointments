<?php

/*
 * Deterministic validator for the migration files in application/migrations.
 *
 * Checks the file-level invariants only — numeric prefix, uniqueness, a gapless
 * sequence, and that each file declares the class CodeIgniter 3 will try to load
 * for it. It does NOT verify that an existing database matches the migration
 * state, and it never renames or modifies migration files.
 *
 * Run directly:
 *     php scripts/validate-migrations.php [path-to-migrations]
 * Exits 0 when valid, 1 otherwise. When included (e.g. from PHPUnit), only the
 * validate_migrations() function is defined and nothing is executed.
 */

/**
 * Validate a migrations directory.
 *
 * @param string $directory Path to the migrations directory.
 *
 * @return array List of error messages (empty means valid).
 */
function validate_migrations(string $directory): array
{
    $errors = [];

    $files = glob(rtrim($directory, '/') . '/*.php');

    if ($files === false || $files === []) {
        return ['Keine Migrationsdateien gefunden in: ' . $directory];
    }

    $by_number = [];

    foreach ($files as $file) {
        $basename = basename($file);

        if (!preg_match('/^(\d{3})_(.+)\.php$/', $basename, $matches)) {
            $errors[] = sprintf('%s: ungültiges Dateinamenpräfix — erwartet NNN_beschreibung.php', $basename);
            continue;
        }

        $number = (int) $matches[1];
        $name = $matches[2];

        if (isset($by_number[$number])) {
            $errors[] = sprintf('%s: doppelte Nummer %03d (auch in %s)', $basename, $number, $by_number[$number]);
        }

        $by_number[$number] = $basename;

        // CodeIgniter 3 derives the class name from the filename minus the numeric prefix.
        $expected_class = 'Migration_' . ucfirst(strtolower($name));

        $content = (string) file_get_contents($file);

        if (!preg_match('/\bclass\s+(\w+)/', $content, $class_match)) {
            $errors[] = sprintf('%s: keine Klasse deklariert — erwartet %s', $basename, $expected_class);
            continue;
        }

        $actual_class = $class_match[1];

        if ($actual_class !== $expected_class) {
            $errors[] = sprintf('%s: Klasse %s — erwartet %s', $basename, $actual_class, $expected_class);
        }

        $quoted = preg_quote($actual_class, '/');

        if (!preg_match('/\bclass\s+' . $quoted . '\b[^\{]*\bextends\s+(EA_Migration|CI_Migration)\b/', $content)) {
            $errors[] = sprintf('%s: Klasse %s muss EA_Migration oder CI_Migration erweitern', $basename, $actual_class);
        }
    }

    if ($by_number !== []) {
        $max = max(array_keys($by_number));

        foreach (range(1, $max) as $expected_number) {
            if (!isset($by_number[$expected_number])) {
                $errors[] = sprintf('fehlende Nummer %03d (Lücke in der Sequenz 001–%03d)', $expected_number, $max);
            }
        }
    }

    return $errors;
}

// Only run the CLI entry point when invoked directly, not when included by a test.
if (PHP_SAPI === 'cli' && isset($argv) && basename($argv[0]) === basename(__FILE__)) {
    $directory = $argv[1] ?? 'application/migrations';

    $errors = validate_migrations($directory);

    if ($errors !== []) {
        fwrite(STDERR, implode(PHP_EOL, $errors) . PHP_EOL);
        exit(1);
    }

    echo 'OK: Migrationen in ' . $directory . ' sind gültig.' . PHP_EOL;
    exit(0);
}
