<?php

namespace Tests\Unit;

use Tests\TestCase;

class AvailabilityTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        if (!class_exists('Availability')) {
            require_once APPPATH . 'libraries/Availability.php';
        }
    }

    public function testCountOverlappingBookingsCountsFullOverlap(): void
    {
        $bookings = [['start_datetime' => '2026-01-05 09:00:00', 'end_datetime' => '2026-01-05 10:00:00']];

        $this->assertSame(
            1,
            \Availability::count_overlapping_bookings($bookings, '2026-01-05 09:30:00', '2026-01-05 09:45:00')
        );
    }

    public function testCountOverlappingBookingsCountsPartialOverlap(): void
    {
        $bookings = [['start_datetime' => '2026-01-05 09:00:00', 'end_datetime' => '2026-01-05 10:00:00']];

        $this->assertSame(
            1,
            \Availability::count_overlapping_bookings($bookings, '2026-01-05 08:30:00', '2026-01-05 09:30:00')
        );
        $this->assertSame(
            1,
            \Availability::count_overlapping_bookings($bookings, '2026-01-05 09:30:00', '2026-01-05 10:30:00')
        );
    }

    public function testCountOverlappingBookingsTreatsBoundariesAsExclusive(): void
    {
        $bookings = [['start_datetime' => '2026-01-05 09:00:00', 'end_datetime' => '2026-01-05 10:00:00']];

        // Booking ends exactly at period start -> no overlap.
        $this->assertSame(
            0,
            \Availability::count_overlapping_bookings($bookings, '2026-01-05 10:00:00', '2026-01-05 10:30:00')
        );

        // Booking starts exactly at period end -> no overlap.
        $this->assertSame(
            0,
            \Availability::count_overlapping_bookings($bookings, '2026-01-05 08:30:00', '2026-01-05 09:00:00')
        );
    }

    public function testCountOverlappingBookingsCountsSlotInsideBooking(): void
    {
        $bookings = [['start_datetime' => '2026-01-05 09:00:00', 'end_datetime' => '2026-01-05 11:00:00']];

        $this->assertSame(
            1,
            \Availability::count_overlapping_bookings($bookings, '2026-01-05 09:30:00', '2026-01-05 10:00:00')
        );
    }

    public function testCountOverlappingBookingsCountsMultipleOverlappingBookings(): void
    {
        $bookings = [
            ['start_datetime' => '2026-01-05 09:00:00', 'end_datetime' => '2026-01-05 10:00:00'],
            ['start_datetime' => '2026-01-05 09:15:00', 'end_datetime' => '2026-01-05 10:15:00'],
            ['start_datetime' => '2026-01-05 11:00:00', 'end_datetime' => '2026-01-05 12:00:00']
        ];

        $this->assertSame(
            2,
            \Availability::count_overlapping_bookings($bookings, '2026-01-05 09:30:00', '2026-01-05 09:45:00')
        );
    }

    // Slot generation: duration 60, interval 15, buffer 15, working day end 18:00 unless stated otherwise.

    private function hours(array $periods, int $buffer = 15, ?string $working_day_end = '18:00'): array
    {
        $periods = array_map(fn(array $p) => ['start' => $p[0], 'end' => $p[1]], $periods);

        return \Availability::generate_hours_for_periods('2026-01-05', $periods, 60, 15, $buffer, $working_day_end);
    }

    public function testEndOfDayBufferAllowsLastSlotEndingExactlyBufferBeforeWorkingDayEnd(): void
    {
        $hours = $this->hours([['09:00', '18:00']]);

        $this->assertSame('16:45', end($hours));
        $this->assertNotContains('17:00', $hours);
    }

    public function testWithoutEndOfDayBufferLastSlotReachesWorkingDayEnd(): void
    {
        $this->assertSame('17:00', current(array_slice($this->hours([['09:00', '18:00']], 0), -1)));
    }

    public function testShortenedDayFromWorkingPlanExceptionUsesItsEnd(): void
    {
        $this->assertSame('12:45', current(array_slice($this->hours([['09:00', '14:00']], 15, '14:00'), -1)));
    }

    public function testNoBufferBeforeBreak(): void
    {
        $hours = $this->hours([['09:00', '12:00'], ['13:00', '18:00']]);

        $this->assertContains('11:00', $hours);
        $this->assertNotContains('11:15', $hours);
        $this->assertSame('16:45', end($hours));
    }

    public function testNoBufferBeforeExistingAppointment(): void
    {
        $this->assertContains('14:00', $this->hours([['09:00', '15:00'], ['16:00', '18:00']]));
    }

    public function testNoBufferBeforeBlockedPeriodOrUnavailabilityReachingTheWorkingDayEnd(): void
    {
        $this->assertSame('16:00', current(array_slice($this->hours([['09:00', '17:00']]), -1)));
        $this->assertSame('15:00', current(array_slice($this->hours([['09:00', '16:00']]), -1)));
    }

    public function testBufferAfterAppointmentIsNotAppliedTwice(): void
    {
        // The period after an appointment ending at 10:00 already starts after its buffer (10:15).
        $hours = $this->hours([['10:15', '18:00']]);

        $this->assertSame('10:15', $hours[0]);
        $this->assertSame('16:45', end($hours));
    }

    public function testPeriodShorterThanDurationPlusBufferAtWorkingDayEndHasNoSlots(): void
    {
        $this->assertSame([], $this->hours([['17:00', '18:00']]));
    }

    public function testEndOfDayBufferForPeriodOnlyAtWorkingDayEnd(): void
    {
        $this->assertSame(15, \Availability::end_of_day_buffer_for_period('18:00', '18:00', 15));
        $this->assertSame(0, \Availability::end_of_day_buffer_for_period('12:00', '18:00', 15));
        $this->assertSame(0, \Availability::end_of_day_buffer_for_period('18:00', null, 15));
    }
}
