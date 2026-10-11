<?php

namespace Tests\Unit;

use DateTimeImmutable;
use DateTimeZone;
use Tests\TestCase;

class BookingPolicyTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        if (!class_exists('Booking_policy')) {
            require_once APPPATH . 'libraries/Booking_policy.php';
        }
    }

    private function policy(string $now, array $settings, string $now_timezone = 'UTC'): \Booking_policy
    {
        return new \Booking_policy([
            'now' => fn() => new DateTimeImmutable($now, new DateTimeZone($now_timezone)),
            'settings' => $settings,
            'format_date_time' => fn(DateTimeImmutable $date) => $date->format('Y-m-d H:i')
        ]);
    }

    private const FIXED = [
        'booking_deadline_mode' => 'fixed_time',
        'booking_deadline_time' => '18:00',
        'booking_deadline_days' => '1'
    ];

    private const MINUTES_60 = ['booking_deadline_mode' => 'minutes', 'book_advance_timeout' => '60'];

    public function testFixedTimeDeadlineIsOpenUntilTheDeadlineMomentInclusive(): void
    {
        $open = $this->policy('2026-01-05 18:00:00', self::FIXED)->get_lock('2026-01-06 10:00:00', 'UTC');
        $locked = $this->policy('2026-01-05 18:00:01', self::FIXED)->get_lock('2026-01-06 10:00:00', 'UTC');

        $this->assertFalse($open['locked']);
        $this->assertTrue($locked['locked']);
        $this->assertSame('2026-01-05 18:00', $locked['limit_text']);
    }

    public function testFixedTimeWithDayOffsetZeroUsesTheSameDay(): void
    {
        $settings = ['booking_deadline_days' => '0'] + self::FIXED;

        $this->assertFalse(
            $this->policy('2026-01-05 17:00:00', $settings)->get_lock('2026-01-05 20:00:00', 'UTC')['locked']
        );
        $this->assertTrue(
            $this->policy('2026-01-05 18:01:00', $settings)->get_lock('2026-01-05 20:00:00', 'UTC')['locked']
        );
    }

    public function testMinutesModeAllowsAStartExactlyAtTheMinimumLeadTime(): void
    {
        $policy = $this->policy('2026-01-05 10:00:00', self::MINUTES_60);

        $this->assertFalse($policy->get_lock('2026-01-05 11:00:00', 'UTC')['locked']);
        $this->assertTrue($policy->get_lock('2026-01-05 10:59:00', 'UTC')['locked']);
        $this->assertSame('01:00', $policy->get_lock('2026-01-05 10:59:00', 'UTC')['limit_text']);

        // Slot filter and lock agree: the earliest bookable start is exactly now + 60 minutes.
        $this->assertSame(
            '2026-01-05 11:00:00',
            $policy->get_earliest_booking_start('2026-01-05', 'UTC')->format('Y-m-d H:i:s')
        );
    }

    public function testManageInheritFollowsTheBookingDeadline(): void
    {
        $policy = $this->policy('2026-01-05 10:00:00', ['manage_deadline_mode' => 'inherit'] + self::MINUTES_60);

        $this->assertSame(
            $policy->get_lock('2026-01-05 10:30:00', 'UTC'),
            $policy->get_lock('2026-01-05 10:30:00', 'UTC', 'manage')
        );
    }

    public function testManageMinutesIsIndependentOfTheBookingDeadline(): void
    {
        $settings = ['manage_deadline_mode' => 'minutes', 'manage_deadline_minutes' => '120'] + self::FIXED;
        $policy = $this->policy('2026-01-05 20:00:00', $settings);

        // Booking deadline for 2026-01-05 passed long ago, the manage deadline (120 minutes) has not.
        $this->assertTrue($policy->get_lock('2026-01-05 23:00:00', 'UTC')['locked']);
        $this->assertFalse($policy->get_lock('2026-01-05 23:00:00', 'UTC', 'manage')['locked']);
        $this->assertTrue($policy->get_lock('2026-01-05 21:00:00', 'UTC', 'manage')['locked']);
    }

    public function testManageFixedTimeUsesItsOwnDeadline(): void
    {
        $settings =
            [
                'manage_deadline_mode' => 'fixed_time',
                'manage_deadline_time' => '12:00',
                'manage_deadline_days' => '0'
            ] + self::MINUTES_60;
        $policy = $this->policy('2026-01-05 12:30:00', $settings);

        $this->assertTrue($policy->get_lock('2026-01-05 15:00:00', 'UTC', 'manage')['locked']);
        $this->assertFalse($policy->get_lock('2026-01-05 15:00:00', 'UTC')['locked']);
    }

    public function testDeadlineIsCalculatedInTheProviderTimezone(): void
    {
        // 17:30 UTC is 18:30 in Berlin (winter time): the Berlin deadline 18:00 has passed, the UTC one has not.
        $this->assertTrue(
            $this->policy('2026-01-05 17:30:00', self::FIXED)->get_lock('2026-01-06 10:00:00', 'Europe/Berlin')[
                'locked'
            ]
        );
        $this->assertFalse(
            $this->policy('2026-01-05 17:30:00', self::FIXED)->get_lock('2026-01-06 10:00:00', 'UTC')['locked']
        );
    }

    public function testDeadlineAcrossDaylightSavingTimeChange(): void
    {
        // Summer time starts on 2026-03-29 at 02:00. For an appointment on 2026-03-29 the deadline is
        // 2026-03-28 18:00 Berlin winter time (CET) = 17:00 UTC, not 16:00 UTC (24 h before 18:00 CEST).
        $settings = self::FIXED;

        $this->assertFalse(
            $this->policy('2026-03-28 16:59:59', $settings)->get_lock('2026-03-29 10:00:00', 'Europe/Berlin')['locked']
        );
        $this->assertFalse(
            $this->policy('2026-03-28 17:00:00', $settings)->get_lock('2026-03-29 10:00:00', 'Europe/Berlin')['locked']
        );
        $this->assertTrue(
            $this->policy('2026-03-28 17:00:01', $settings)->get_lock('2026-03-29 10:00:00', 'Europe/Berlin')['locked']
        );
    }

    public function testNonNumericDaySettingCountsAsZeroLikeBeforeTheExtraction(): void
    {
        $settings = ['booking_deadline_days' => 'abc'] + self::FIXED;

        // Day offset 0: the deadline is on the appointment day itself (2026-01-06 18:00).
        $this->assertFalse(
            $this->policy('2026-01-06 09:00:00', $settings)->get_lock('2026-01-06 20:00:00', 'UTC')['locked']
        );
    }

    public function testFixedTimeLocksTheWholeDayAfterTheDeadline(): void
    {
        $this->assertNull(
            $this->policy('2026-01-05 18:00:01', self::FIXED)->get_earliest_booking_start('2026-01-06', 'UTC')
        );

        $earliest = $this->policy('2026-01-05 17:00:00', self::FIXED)->get_earliest_booking_start('2026-01-06', 'UTC');

        // No minimum lead time in fixed time mode: only the past is excluded.
        $this->assertSame('2026-01-05 17:00:00', $earliest->format('Y-m-d H:i:s'));
    }

    public function testLockMessageInsertsTheLimitOnly(): void
    {
        $lock = ['locked' => true, 'limit_text' => '02:00', 'message' => 'Not less than {$limit} before.'];

        $this->assertSame('Not less than 02:00 before.', \Booking_policy::lock_message($lock));
        $this->assertSame('Not less than 02:00 before.', \Booking_policy::lock_page_vars($lock)['message_text']);
    }
}
