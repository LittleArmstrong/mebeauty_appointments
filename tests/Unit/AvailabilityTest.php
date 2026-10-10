<?php

namespace Tests\Unit;

use Tests\TestCase;

class AvailabilityTest extends TestCase {
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        if (!class_exists('Availability')) {
            require_once APPPATH . 'libraries/Availability.php';
        }
    }

    public function testCountOverlappingBookingsCountsFullOverlap(): void
    {
        $bookings = [
            ['start_datetime' => '2026-01-05 09:00:00', 'end_datetime' => '2026-01-05 10:00:00'],
        ];

        $this->assertSame(1, \Availability::count_overlapping_bookings($bookings, '2026-01-05 09:30:00', '2026-01-05 09:45:00'));
    }

    public function testCountOverlappingBookingsCountsPartialOverlap(): void
    {
        $bookings = [
            ['start_datetime' => '2026-01-05 09:00:00', 'end_datetime' => '2026-01-05 10:00:00'],
        ];

        $this->assertSame(1, \Availability::count_overlapping_bookings($bookings, '2026-01-05 08:30:00', '2026-01-05 09:30:00'));
        $this->assertSame(1, \Availability::count_overlapping_bookings($bookings, '2026-01-05 09:30:00', '2026-01-05 10:30:00'));
    }

    public function testCountOverlappingBookingsTreatsBoundariesAsExclusive(): void
    {
        $bookings = [
            ['start_datetime' => '2026-01-05 09:00:00', 'end_datetime' => '2026-01-05 10:00:00'],
        ];

        // Booking ends exactly at period start -> no overlap.
        $this->assertSame(0, \Availability::count_overlapping_bookings($bookings, '2026-01-05 10:00:00', '2026-01-05 10:30:00'));

        // Booking starts exactly at period end -> no overlap.
        $this->assertSame(0, \Availability::count_overlapping_bookings($bookings, '2026-01-05 08:30:00', '2026-01-05 09:00:00'));
    }

    public function testCountOverlappingBookingsCountsSlotInsideBooking(): void
    {
        $bookings = [
            ['start_datetime' => '2026-01-05 09:00:00', 'end_datetime' => '2026-01-05 11:00:00'],
        ];

        $this->assertSame(1, \Availability::count_overlapping_bookings($bookings, '2026-01-05 09:30:00', '2026-01-05 10:00:00'));
    }

    public function testCountOverlappingBookingsCountsMultipleOverlappingBookings(): void
    {
        $bookings = [
            ['start_datetime' => '2026-01-05 09:00:00', 'end_datetime' => '2026-01-05 10:00:00'],
            ['start_datetime' => '2026-01-05 09:15:00', 'end_datetime' => '2026-01-05 10:15:00'],
            ['start_datetime' => '2026-01-05 11:00:00', 'end_datetime' => '2026-01-05 12:00:00'],
        ];

        $this->assertSame(2, \Availability::count_overlapping_bookings($bookings, '2026-01-05 09:30:00', '2026-01-05 09:45:00'));
    }
}
