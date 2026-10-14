<?php

/*
 * Generator and validator for the local spec tracker in .scratch/.
 *
 * Every spec lives in .scratch/NNNN-<slug>/ with a spec.md (or map.md for wayfinding efforts)
 * whose header lines carry Status, Created, Closed, Priority and Depends-on. Tickets live in
 * issues/NN-<slug>.md with a Status line. See docs/agents/issue-tracker.md for the schema.
 *
 * Run directly:
 *     php scripts/scratch-index.php generate [path-to-scratch]   # (re)write INDEX.md
 *     php scripts/scratch-index.php check [path-to-scratch]      # validate, exit 1 on errors
 * When included (e.g. from PHPUnit), only the functions are defined and nothing is executed.
 */

const SCRATCH_STATUSES = [
    'needs-triage',
    'needs-info',
    'ready-for-agent',
    'ready-for-human',
    'in-progress',
    'needs-verification',
    'resolved',
    'wontfix'
];

const SCRATCH_CLOSED_STATUSES = ['resolved', 'wontfix'];

const SCRATCH_PRIORITIES = ['P1', 'P2', 'P3'];

const SCRATCH_HEADER_KEYS = ['Status', 'Created', 'Closed', 'Priority', 'Depends-on'];

const SCRATCH_INDEX_FILE = 'INDEX.md';

/**
 * Ticket statuses that claim finished work and so need the red evidence of CODING_STANDARDS.md "Tests go red".
 */
const SCRATCH_RED_EVIDENCE_STATUSES = ['needs-verification', 'resolved'];

/**
 * First spec written after the rule "Tests go red" (0016/05); earlier tickets predate it.
 */
const SCRATCH_RED_EVIDENCE_FROM_SPEC = 17;

/**
 * Check whether a header value means "none" (en dash or hyphen).
 *
 * @param string $value Header value.
 *
 * @return bool
 */
function scratch_is_none(string $value): bool
{
    return in_array($value, ['–', '-'], true);
}

/**
 * Check whether a spec or ticket status counts as closed.
 *
 * @param string|null $status Status value.
 *
 * @return bool
 */
function scratch_is_closed(?string $status): bool
{
    return in_array($status, SCRATCH_CLOSED_STATUSES, true);
}

/**
 * Parse the title and header lines of a spec file.
 *
 * The header is the block of "Key: value" lines that follows the first "# " title line.
 *
 * @param string $content Spec file content.
 *
 * @return array{title: string, header: array<string, string>}
 */
function scratch_parse_spec(string $content): array
{
    $title = '';
    $header = [];
    $in_header = false;

    foreach (preg_split('/\R/', $content) as $line) {
        if ($title === '') {
            if (str_starts_with($line, '# ')) {
                $title = trim(substr($line, 2));
                $in_header = true;
            }

            continue;
        }

        if (!$in_header) {
            break;
        }

        if (trim($line) === '') {
            if ($header !== []) {
                break;
            }

            continue;
        }

        if (!preg_match('/^([A-Za-z-]+):\s*(.*)$/', $line, $matches)) {
            break;
        }

        $header[$matches[1]] = trim($matches[2]);
    }

    return ['title' => $title, 'header' => $header];
}

/**
 * Extract the status of a ticket file ("**Status:** value" or "Status: value").
 *
 * @param string $content Ticket file content.
 *
 * @return string|null Returns the status or null when the ticket has none.
 */
function scratch_ticket_status(string $content): ?string
{
    if (preg_match('/^(?:\*\*Status:\*\*|Status:)\s*([A-Za-z-]+)/m', $content, $matches)) {
        return $matches[1];
    }

    return null;
}

/**
 * Check whether a ticket records its red evidence ("Rot vor Fix: …", or "Rot vor Fix: entfällt – <reason>" for
 * refactor and docs tickets).
 *
 * @param string $content Ticket file content.
 *
 * @return bool
 */
function scratch_ticket_has_red_evidence(string $content): bool
{
    return preg_match('/^\s*(?:[-*]\s+)?Rot vor Fix:\s*\S/m', $content) === 1;
}

/**
 * Read all spec directories of a scratch directory.
 *
 * @param string $directory Path to the scratch directory.
 *
 * @return array{specs: array, errors: string[]}
 */
function scratch_collect(string $directory): array
{
    $specs = [];
    $errors = [];

    $entries = glob(rtrim($directory, '/') . '/*', GLOB_ONLYDIR) ?: [];

    foreach ($entries as $path) {
        $name = basename($path);

        if (!preg_match('/^(\d{4})-([a-z0-9][a-z0-9-]*)$/', $name, $matches)) {
            $errors[] = sprintf('%s: ungültiger Ordnername — erwartet NNNN-slug', $name);
            continue;
        }

        $spec_file = is_file($path . '/spec.md') ? 'spec.md' : (is_file($path . '/map.md') ? 'map.md' : null);

        if ($spec_file === null) {
            $errors[] = sprintf('%s: weder spec.md noch map.md vorhanden', $name);
            continue;
        }

        $parsed = scratch_parse_spec((string) file_get_contents($path . '/' . $spec_file));

        $tickets = [];
        $red_evidence = [];

        foreach (glob($path . '/issues/*.md') ?: [] as $ticket_path) {
            $content = (string) file_get_contents($ticket_path);
            $tickets[basename($ticket_path)] = scratch_ticket_status($content);
            $red_evidence[basename($ticket_path)] = scratch_ticket_has_red_evidence($content);
        }

        ksort($tickets);

        $specs[] = [
            'number' => (int) $matches[1],
            'dir' => $name,
            'file' => $spec_file,
            'title' => $parsed['title'],
            'header' => $parsed['header'],
            'tickets' => $tickets,
            'red_evidence' => $red_evidence
        ];
    }

    usort($specs, fn(array $a, array $b) => $a['number'] <=> $b['number']);

    return ['specs' => $specs, 'errors' => $errors];
}

/**
 * Count the open tickets of a spec.
 *
 * @param array $spec Spec record from scratch_collect().
 *
 * @return int
 */
function scratch_open_tickets(array $spec): int
{
    return count(array_filter($spec['tickets'], fn(?string $status) => !scratch_is_closed($status)));
}

/**
 * Check whether a string is a valid YYYY-MM-DD date.
 *
 * @param string $value Date string.
 *
 * @return bool
 */
function scratch_is_date(string $value): bool
{
    $date = DateTime::createFromFormat('!Y-m-d', $value);

    return $date !== false && $date->format('Y-m-d') === $value;
}

/**
 * Validate the collected specs against the tracker schema.
 *
 * @param array $specs Spec records from scratch_collect().
 *
 * @return string[] List of error messages (empty means valid).
 */
function scratch_validate(array $specs): array
{
    $errors = [];
    $numbers = [];

    foreach ($specs as $spec) {
        $numbers[$spec['number']][] = $spec['dir'];
    }

    foreach ($numbers as $number => $dirs) {
        if (count($dirs) > 1) {
            $errors[] = sprintf('doppelte Nummer %04d: %s', $number, implode(', ', $dirs));
        }
    }

    if ($numbers !== []) {
        foreach (range(1, max(array_keys($numbers))) as $expected) {
            if (!isset($numbers[$expected])) {
                $errors[] = sprintf('fehlende Nummer %04d (Lücke in der Sequenz)', $expected);
            }
        }
    }

    foreach ($specs as $spec) {
        $label = $spec['dir'] . '/' . $spec['file'];
        $header = $spec['header'];

        if ($spec['title'] === '') {
            $errors[] = sprintf('%s: kein Titel (# …) gefunden', $label);
        }

        foreach (SCRATCH_HEADER_KEYS as $key) {
            if (!array_key_exists($key, $header)) {
                $errors[] = sprintf('%s: Kopfzeile %s fehlt', $label, $key);
            }
        }

        $status = $header['Status'] ?? null;
        $closed_status = scratch_is_closed($status);

        if ($status !== null && !in_array($status, SCRATCH_STATUSES, true)) {
            $errors[] = sprintf('%s: unbekannter Status "%s"', $label, $status);
        }

        if (isset($header['Created']) && !scratch_is_date($header['Created'])) {
            $errors[] = sprintf('%s: Created "%s" ist kein Datum (YYYY-MM-DD)', $label, $header['Created']);
        }

        if (isset($header['Closed'])) {
            if ($closed_status && !scratch_is_date($header['Closed'])) {
                $errors[] = sprintf('%s: Closed muss bei Status %s ein Datum sein', $label, $status);
            }

            if (!$closed_status && $status !== null && !scratch_is_none($header['Closed'])) {
                $errors[] = sprintf('%s: Closed muss bei offenem Status "–" sein', $label);
            }
        }

        if (isset($header['Priority'])) {
            $priority = $header['Priority'];

            if (!in_array($priority, SCRATCH_PRIORITIES, true) && !scratch_is_none($priority)) {
                $errors[] = sprintf('%s: unbekannte Priority "%s"', $label, $priority);
            }

            if (scratch_is_none($priority) && !$closed_status && $status !== null) {
                $errors[] = sprintf('%s: offene Specs brauchen eine Priority (P1–P3)', $label);
            }
        }

        if (isset($header['Depends-on']) && !scratch_is_none($header['Depends-on'])) {
            foreach (array_map('trim', explode(',', $header['Depends-on'])) as $dependency) {
                if (!preg_match('/^\d{4}$/', $dependency)) {
                    $errors[] = sprintf('%s: Depends-on "%s" ist keine Spec-Nummer', $label, $dependency);
                } elseif (!isset($numbers[(int) $dependency])) {
                    $errors[] = sprintf('%s: Depends-on verweist auf unbekannte Spec %s', $label, $dependency);
                }
            }
        }

        foreach ($spec['tickets'] as $ticket => $ticket_status) {
            if ($ticket_status === null) {
                $errors[] = sprintf('%s/issues/%s: Status-Zeile fehlt', $spec['dir'], $ticket);
            }

            if (
                $spec['number'] >= SCRATCH_RED_EVIDENCE_FROM_SPEC &&
                in_array($ticket_status, SCRATCH_RED_EVIDENCE_STATUSES, true) &&
                !($spec['red_evidence'][$ticket] ?? false)
            ) {
                $errors[] = sprintf(
                    '%s/issues/%s: Beleg "Rot vor Fix:" fehlt (CODING_STANDARDS.md "Tests go red"; bei Refactor/Doku "Rot vor Fix: entfällt – <Grund>")',
                    $spec['dir'],
                    $ticket
                );
            }
        }

        $open_tickets = scratch_open_tickets($spec);

        if ($status === 'resolved' && $open_tickets > 0) {
            $errors[] = sprintf('%s: Status resolved, aber %d Ticket(s) offen', $label, $open_tickets);
        }
    }

    return $errors;
}

/**
 * Render the INDEX.md content.
 *
 * @param array $specs Spec records from scratch_collect().
 *
 * @return string
 */
function scratch_render_index(array $specs): string
{
    $open = [];
    $closed = [];

    foreach ($specs as $spec) {
        if (scratch_is_closed($spec['header']['Status'] ?? null)) {
            $closed[] = $spec;
        } else {
            $open[] = $spec;
        }
    }

    $priority_rank = fn(array $spec) => array_search($spec['header']['Priority'] ?? '', SCRATCH_PRIORITIES, true);

    usort($open, function (array $a, array $b) use ($priority_rank) {
        $rank_a = $priority_rank($a);
        $rank_b = $priority_rank($b);

        $rank_a = $rank_a === false ? PHP_INT_MAX : $rank_a;
        $rank_b = $rank_b === false ? PHP_INT_MAX : $rank_b;

        return [$rank_a, $a['number']] <=> [$rank_b, $b['number']];
    });

    $spec_link = fn(array $spec) => sprintf(
        '[%s](%s/%s) %s',
        str_pad((string) $spec['number'], 4, '0', STR_PAD_LEFT),
        $spec['dir'],
        $spec['file'],
        str_replace('|', '\|', $spec['title'])
    );

    $ticket_summary = fn(array $spec) => sprintf('%d/%d', scratch_open_tickets($spec), count($spec['tickets']));

    $header_value = fn(array $spec, string $key) => $spec['header'][$key] ?? '?';

    $lines = [
        '# Spec-Index',
        '',
        '> Generiert von `composer scratch:index` — nicht von Hand bearbeiten. Schema: `docs/agents/issue-tracker.md`.',
        '',
        '## Offen',
        ''
    ];

    if ($open === []) {
        $lines[] = 'Keine offenen Specs.';
    } else {
        $lines[] = '| Spec | Status | Priorität | Erstellt | Abhängig von | Tickets offen/gesamt |';
        $lines[] = '|---|---|---|---|---|---|';

        foreach ($open as $spec) {
            $lines[] = sprintf(
                '| %s | %s | %s | %s | %s | %s |',
                $spec_link($spec),
                $header_value($spec, 'Status'),
                $header_value($spec, 'Priority'),
                $header_value($spec, 'Created'),
                $header_value($spec, 'Depends-on'),
                $ticket_summary($spec)
            );
        }
    }

    $lines[] = '';
    $lines[] = '## Abgeschlossen';
    $lines[] = '';

    if ($closed === []) {
        $lines[] = 'Keine abgeschlossenen Specs.';
    } else {
        $lines[] = '| Spec | Status | Erstellt | Abgeschlossen | Tickets |';
        $lines[] = '|---|---|---|---|---|';

        foreach ($closed as $spec) {
            $lines[] = sprintf(
                '| %s | %s | %s | %s | %d |',
                $spec_link($spec),
                $header_value($spec, 'Status'),
                $header_value($spec, 'Created'),
                $header_value($spec, 'Closed'),
                count($spec['tickets'])
            );
        }
    }

    return implode("\n", $lines) . "\n";
}

/**
 * Validate a scratch directory including the freshness of its INDEX.md.
 *
 * @param string $directory Path to the scratch directory.
 *
 * @return string[] List of error messages (empty means valid).
 */
function scratch_check(string $directory): array
{
    $collected = scratch_collect($directory);

    $errors = array_merge($collected['errors'], scratch_validate($collected['specs']));

    $index_path = rtrim($directory, '/') . '/' . SCRATCH_INDEX_FILE;

    $expected = scratch_render_index($collected['specs']);

    if (!is_file($index_path)) {
        $errors[] = SCRATCH_INDEX_FILE . ' fehlt — mit `composer scratch:index` erzeugen';
    } elseif ((string) file_get_contents($index_path) !== $expected) {
        $errors[] = SCRATCH_INDEX_FILE . ' ist veraltet — mit `composer scratch:index` neu erzeugen';
    }

    return $errors;
}

// Only run the CLI entry point when invoked directly, not when included by a test.
if (PHP_SAPI === 'cli' && isset($argv) && basename($argv[0]) === basename(__FILE__)) {
    $command = $argv[1] ?? 'check';
    $directory = $argv[2] ?? '.scratch';

    if ($command === 'generate') {
        $collected = scratch_collect($directory);

        file_put_contents(rtrim($directory, '/') . '/' . SCRATCH_INDEX_FILE, scratch_render_index($collected['specs']));

        $errors = array_merge($collected['errors'], scratch_validate($collected['specs']));

        echo 'INDEX.md erzeugt (' . count($collected['specs']) . ' Specs).' . PHP_EOL;

        if ($errors !== []) {
            fwrite(STDERR, 'Hinweis — Schema-Verstöße:' . PHP_EOL . implode(PHP_EOL, $errors) . PHP_EOL);
        }

        exit(0);
    }

    if ($command !== 'check') {
        fwrite(STDERR, 'Unbekannter Befehl: ' . $command . ' (erwartet generate|check)' . PHP_EOL);
        exit(2);
    }

    $errors = scratch_check($directory);

    if ($errors !== []) {
        fwrite(STDERR, implode(PHP_EOL, $errors) . PHP_EOL);
        exit(1);
    }

    echo 'OK: ' . $directory . ' entspricht dem Tracker-Schema.' . PHP_EOL;
    exit(0);
}
