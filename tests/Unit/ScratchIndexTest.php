<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ScratchIndexTest extends TestCase
{
    private string $tmpDir;

    public static function setUpBeforeClass(): void
    {
        require_once __DIR__ . '/../../scripts/scratch-index.php';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->tmpDir = sys_get_temp_dir() . '/scratch-index-' . bin2hex(random_bytes(4));

        mkdir($this->tmpDir);
    }

    protected function tearDown(): void
    {
        $this->removeDir($this->tmpDir);

        parent::tearDown();
    }

    public function testValidScratchWithFreshIndexPasses(): void
    {
        $this->writeSpec('0001-first', $this->header('resolved', '2026-09-30', '2026-10-01', '–', '–'));
        $this->writeTicket('0001-first', '01-a.md', 'resolved');
        $this->writeSpec('0002-second', $this->header('ready-for-agent', '2026-10-02', '–', 'P1', '0001'));
        $this->writeTicket('0002-second', '01-b.md', 'ready-for-agent');

        $this->generateIndex();

        $this->assertSame([], \scratch_check($this->tmpDir));
    }

    public function testMissingStatusIsReported(): void
    {
        $this->writeSpec('0001-first', "# Title\n\nCreated: 2026-10-01\nClosed: –\nPriority: P2\nDepends-on: –\n");

        $this->assertErrorContains('Kopfzeile Status fehlt');
    }

    public function testUnknownStatusIsReported(): void
    {
        $this->writeSpec('0001-first', $this->header('done', '2026-10-01', '–', 'P2', '–'));

        $this->assertErrorContains('unbekannter Status "done"');
    }

    public function testInvalidDateIsReported(): void
    {
        $this->writeSpec('0001-first', $this->header('ready-for-agent', '2026-13-01', '–', 'P2', '–'));

        $this->assertErrorContains('Created "2026-13-01" ist kein Datum');
    }

    public function testClosedSpecNeedsClosedDate(): void
    {
        $this->writeSpec('0001-first', $this->header('resolved', '2026-10-01', '–', '–', '–'));

        $this->assertErrorContains('Closed muss bei Status resolved ein Datum sein');
    }

    public function testOpenSpecNeedsPriority(): void
    {
        $this->writeSpec('0001-first', $this->header('ready-for-agent', '2026-10-01', '–', '–', '–'));

        $this->assertErrorContains('offene Specs brauchen eine Priority');
    }

    public function testUnknownDependencyIsReported(): void
    {
        $this->writeSpec('0001-first', $this->header('ready-for-agent', '2026-10-01', '–', 'P2', '0007'));

        $this->assertErrorContains('unbekannte Spec 0007');
    }

    public function testResolvedSpecWithOpenTicketIsReported(): void
    {
        $this->writeSpec('0001-first', $this->header('resolved', '2026-10-01', '2026-10-02', '–', '–'));
        $this->writeTicket('0001-first', '01-a.md', 'ready-for-agent');

        $this->assertErrorContains('Status resolved, aber 1 Ticket(s) offen');
    }

    public function testDuplicateNumberIsReported(): void
    {
        $this->writeSpec('0001-first', $this->header('ready-for-agent', '2026-10-01', '–', 'P2', '–'));
        $this->writeSpec('0001-other', $this->header('ready-for-agent', '2026-10-01', '–', 'P2', '–'));

        $this->assertErrorContains('doppelte Nummer 0001');
    }

    public function testGapInNumbersIsReported(): void
    {
        $this->writeSpec('0001-first', $this->header('ready-for-agent', '2026-10-01', '–', 'P2', '–'));
        $this->writeSpec('0003-third', $this->header('ready-for-agent', '2026-10-01', '–', 'P2', '–'));

        $this->assertErrorContains('fehlende Nummer 0002');
    }

    public function testUnnumberedDirectoryIsReported(): void
    {
        $this->writeSpec('legacy-spec', $this->header('ready-for-agent', '2026-10-01', '–', 'P2', '–'));

        $this->assertErrorContains('legacy-spec: ungültiger Ordnername');
    }

    public function testOutdatedIndexIsReported(): void
    {
        $this->writeSpec('0001-first', $this->header('ready-for-agent', '2026-10-01', '–', 'P2', '–'));

        $this->generateIndex();

        $this->writeTicket('0001-first', '01-a.md', 'ready-for-agent');

        $this->assertErrorContains('INDEX.md ist veraltet');
    }

    public function testIndexListsOpenSpecsByPriorityAndCountsTickets(): void
    {
        $this->writeSpec('0001-low', $this->header('ready-for-agent', '2026-10-01', '–', 'P3', '–'));
        $this->writeSpec('0002-high', $this->header('in-progress', '2026-10-02', '–', 'P1', '–'));
        $this->writeTicket('0002-high', '01-a.md', 'resolved');
        $this->writeTicket('0002-high', '02-b.md', 'ready-for-agent');
        $this->writeSpec('0003-done', $this->header('resolved', '2026-10-03', '2026-10-04', '–', '–'));

        $index = \scratch_render_index(\scratch_collect($this->tmpDir)['specs']);

        $this->assertLessThan(strpos($index, '0001-low'), strpos($index, '0002-high'));
        $this->assertStringContainsString('| in-progress | P1 | 2026-10-02 | – | 1/2 |', $index);
        $this->assertGreaterThan(strpos($index, '## Abgeschlossen'), strpos($index, '0003-done'));
    }

    public function testParseSpecReadsHeaderBlockOnly(): void
    {
        $parsed = \scratch_parse_spec(
            "# My Spec\n\nStatus: resolved\nCreated: 2026-10-01\n\n## Problem\n\nKey: not header\n"
        );

        $this->assertSame('My Spec', $parsed['title']);
        $this->assertSame(['Status' => 'resolved', 'Created' => '2026-10-01'], $parsed['header']);
    }

    public function testTicketStatusSupportsBoldAndPlainFormat(): void
    {
        $this->assertSame('resolved', \scratch_ticket_status("# 01\n\n**Status:** resolved\n"));
        $this->assertSame('claimed', \scratch_ticket_status("# 01\nStatus: claimed\n"));
        $this->assertNull(\scratch_ticket_status("# 01\n"));
    }

    private function header(string $status, string $created, string $closed, string $priority, string $depends): string
    {
        return "# Title\n\nStatus: $status\nCreated: $created\nClosed: $closed\nPriority: $priority\nDepends-on: $depends\n\n## Problem Statement\n";
    }

    private function writeSpec(string $dir, string $content): void
    {
        @mkdir($this->tmpDir . '/' . $dir . '/issues', 0777, true);

        file_put_contents($this->tmpDir . '/' . $dir . '/spec.md', $content);
    }

    private function writeTicket(string $dir, string $file, string $status): void
    {
        file_put_contents($this->tmpDir . '/' . $dir . '/issues/' . $file, "# Ticket\n\n**Status:** $status\n");
    }

    private function generateIndex(): void
    {
        file_put_contents($this->tmpDir . '/INDEX.md', \scratch_render_index(\scratch_collect($this->tmpDir)['specs']));
    }

    private function assertErrorContains(string $needle): void
    {
        $this->assertStringContainsString($needle, implode("\n", \scratch_check($this->tmpDir)));
    }

    private function removeDir(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $items = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($items as $item) {
            $item->isDir() ? rmdir($item->getPathname()) : unlink($item->getPathname());
        }

        rmdir($dir);
    }
}
