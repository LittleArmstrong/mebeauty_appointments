<?php

/*
 * Validator for unescaped output in fork-owned views.
 *
 * Reports every `<?= … ?>` in application/views/ whose expression starts with a variable, setting(), vars() or
 * html_vars() instead of going through e(). Only the start of the expression is inspected (`<?= e($a) . $b ?>`
 * passes): the check catches the common case, review covers the rest. Only fork-owned views are checked (added
 * since the upstream base commit in UPSTREAM.md, plus untracked files); upstream views keep their upstream form
 * (ADR 0005).
 *
 * Run directly:
 *     php scripts/validate-view-escaping.php [repo-root]
 * Exits 0 when valid, 1 on findings, 2 when the upstream base commit is not available (shallow clone).
 * When included (e.g. from PHPUnit), only the functions are defined and nothing is executed.
 */

/**
 * Find unescaped output tags in view content.
 *
 * @param string $content View source.
 *
 * @return array List of ['line' => int, 'output' => string] (empty means valid).
 */
function find_unescaped_view_output(string $content): array
{
    $findings = [];

    preg_match_all('/<\?=\s*(.*?)\s*\?>/s', $content, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

    foreach ($matches as $match) {
        if (!preg_match('/^(\$|setting\(|vars\(|html_vars\()/', $match[1][0])) {
            continue;
        }

        $findings[] = [
            'line' => substr_count($content, "\n", 0, $match[0][1]) + 1,
            'output' => $match[0][0]
        ];
    }

    return $findings;
}

/**
 * List the fork-owned view files (relative paths), or null when the upstream base commit is not available.
 *
 * @param string $root Repository root directory.
 *
 * @return array|null
 */
function fork_owned_view_files(string $root): ?array
{
    $upstream = (string) @file_get_contents($root . '/UPSTREAM.md');

    if (!preg_match('/[0-9a-f]{40}/', $upstream, $base)) {
        return null;
    }

    $git = 'git -C ' . escapeshellarg($root) . ' ';

    exec($git . 'cat-file -e ' . escapeshellarg($base[0] . '^{commit}') . ' 2>/dev/null', $output, $status);

    if ($status !== 0) {
        return null;
    }

    $added = (string) shell_exec(
        $git . 'diff --name-only --diff-filter=A ' . escapeshellarg($base[0]) . " -- 'application/views/*.php'"
    );
    $untracked = (string) shell_exec($git . "ls-files --others --exclude-standard -- 'application/views/*.php'");

    $files = array_unique(array_filter(explode("\n", $added . "\n" . $untracked)));

    $files = array_filter($files, fn(string $file) => is_file($root . '/' . $file));

    sort($files);

    return $files;
}

// Only run the CLI entry point when invoked directly, not when included by a test.
if (PHP_SAPI === 'cli' && isset($argv) && basename($argv[0]) === basename(__FILE__)) {
    $root = rtrim($argv[1] ?? getcwd(), '/');

    $files = fork_owned_view_files($root);

    if ($files === null) {
        fwrite(STDERR, 'check:views: upstream base commit from UPSTREAM.md not available (shallow clone?)' . PHP_EOL);
        exit(2);
    }

    $errors = [];

    foreach ($files as $file) {
        foreach (find_unescaped_view_output((string) file_get_contents($root . '/' . $file)) as $finding) {
            $errors[] = sprintf('%s:%d: %s (Ausgabe ohne e())', $file, $finding['line'], $finding['output']);
        }
    }

    if ($errors) {
        fwrite(STDERR, implode(PHP_EOL, $errors) . PHP_EOL);
        fwrite(STDERR, sprintf('check:views: %d ungeescapte Ausgabe(n) in Fork-Views.', count($errors)) . PHP_EOL);
        exit(1);
    }

    echo sprintf('OK: %d Fork-Views ohne ungeescapte Ausgabe.', count($files)) . PHP_EOL;
    exit(0);
}
