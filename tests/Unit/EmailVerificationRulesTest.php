<?php

namespace Tests\Unit;

use DateTimeImmutable;
use Tests\TestCase;

class EmailVerificationRulesTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        if (!class_exists('Email_verification_rules')) {
            require_once APPPATH . 'libraries/Email_verification_rules.php';
        }
    }

    private function rules(): \Email_verification_rules
    {
        return new \Email_verification_rules();
    }

    private const APPOINTMENT = [
        'start_datetime' => '2026-11-02 10:00:00',
        'end_datetime' => '2026-11-02 11:00:00',
        'id_services' => 3,
        'id_users_provider' => 2,
        'notes' => 'Bitte ruhig',
        'location' => 'Salon'
    ];

    private const CUSTOMER = [
        'first_name' => 'Lena',
        'last_name' => 'Neu',
        'email' => 'Lena@Example.com',
        'phone_number' => '0170 222',
        'timezone' => 'Europe/Berlin',
        'language' => 'german'
    ];

    public function testNewBookingWithoutTheSettingNeedsNoVerification(): void
    {
        $this->assertFalse($this->rules()->requires_verification(false, false, 'lena@example.com', []));
    }

    public function testNewBookingWithTheSettingNeedsVerification(): void
    {
        $this->assertTrue($this->rules()->requires_verification(true, false, 'lena@example.com', []));
    }

    public function testRescheduleNeedsNoVerification(): void
    {
        $this->assertFalse($this->rules()->requires_verification(true, true, 'lena@example.com', []));
    }

    public function testEmailVerifiedInThisSessionNeedsNoVerificationRegardlessOfCase(): void
    {
        $this->assertFalse(
            $this->rules()->requires_verification(true, false, ' Lena@Example.COM ', ['lena@example.com'])
        );
    }

    public function testOtherVerifiedEmailDoesNotCount(): void
    {
        $this->assertTrue($this->rules()->requires_verification(true, false, 'anna@example.com', ['lena@example.com']));
    }

    public function testEmptyEmailIsNeverVerified(): void
    {
        $this->assertTrue($this->rules()->requires_verification(true, false, '', ['']));
        $this->assertTrue($this->rules()->requires_verification(true, false, null, []));
    }

    public function testRememberVerifiedAddsTheNormalizedEmailOnce(): void
    {
        $verified = $this->rules()->remember_verified(['anna@example.com'], 'Lena@Example.com ');
        $verified = $this->rules()->remember_verified($verified, 'lena@example.com');

        $this->assertSame(['anna@example.com', 'lena@example.com'], $verified);
    }

    public function testRememberVerifiedIgnoresGarbageFromTheSession(): void
    {
        $this->assertSame(['lena@example.com'], $this->rules()->remember_verified(null, 'lena@example.com'));
    }

    public function testBookingVerificationExpiresAfterTenMinutesAndStoresOnlyTheTokenHash(): void
    {
        $token = str_repeat('ab', 32);

        $record = $this->rules()->booking_verification(
            self::APPOINTMENT,
            self::CUSTOMER,
            $token,
            new DateTimeImmutable('2026-10-14 12:00:00')
        );

        $this->assertSame('booking', $record['purpose']);
        $this->assertSame('lena@example.com', $record['email']);
        $this->assertSame('2026-10-14 12:10:00', $record['expires_datetime']);
        $this->assertSame('2026-10-14 12:00:00', $record['last_sent_datetime']);
        $this->assertSame(1, $record['send_count']);
        $this->assertSame(hash('sha256', $token), $record['token_hash']);
        $this->assertNotContains($token, $record);
    }

    public function testBookingVerificationKeepsTheDataToRestoreTheBooking(): void
    {
        $record = $this->rules()->booking_verification(
            self::APPOINTMENT,
            self::CUSTOMER,
            'token',
            new DateTimeImmutable('2026-10-14 12:00:00')
        );

        $this->assertSame(3, $record['id_services']);
        $this->assertSame(2, $record['id_users_provider']);
        $this->assertSame('2026-11-02 10:00:00', $record['start_datetime']);
        $this->assertSame('2026-11-02 11:00:00', $record['end_datetime']);
        $this->assertSame('Bitte ruhig', $record['notes']);
        $this->assertSame(self::CUSTOMER, json_decode($record['customer_data'], true));
    }

    public function testTokenHashMatchesTheStoredHashOnlyForTheSameToken(): void
    {
        $rules = $this->rules();

        $this->assertSame($rules->token_hash('abc'), $rules->token_hash('abc'));
        $this->assertNotSame($rules->token_hash('abc'), $rules->token_hash('abd'));
        $this->assertSame(64, strlen($rules->token_hash('abc')));
    }

    public function testCustomerInputComesBackFromTheStoredVerification(): void
    {
        $record = $this->rules()->booking_verification(
            self::APPOINTMENT,
            self::CUSTOMER,
            'token',
            new DateTimeImmutable('2026-10-14 12:00:00')
        );

        $this->assertSame(self::CUSTOMER, $this->rules()->customer_input($record));
        $this->assertSame([], $this->rules()->customer_input(['customer_data' => 'not json']));
    }

    private const SENT = ['expires_datetime' => '2026-10-14 12:10:00'];

    public function testVormerkungIsValidUntilTenMinutesAfterTheMail(): void
    {
        $this->assertSame(
            \Email_verification_rules::STATE_VALID,
            $this->rules()->state(self::SENT, new DateTimeImmutable('2026-10-14 12:09:59'))
        );
    }

    public function testVormerkungIsExpiredButRestorableFromTenMinutesAfterTheMail(): void
    {
        $this->assertSame(
            \Email_verification_rules::STATE_RESTORABLE,
            $this->rules()->state(self::SENT, new DateTimeImmutable('2026-10-14 12:10:00'))
        );
    }

    public function testVormerkungIsRestorableUntilTwentyFourHoursAfterTheMail(): void
    {
        $this->assertSame(
            \Email_verification_rules::STATE_RESTORABLE,
            $this->rules()->state(self::SENT, new DateTimeImmutable('2026-10-15 11:59:59'))
        );
    }

    public function testVormerkungIsVoidTwentyFourHoursAfterTheMail(): void
    {
        $this->assertSame(
            \Email_verification_rules::STATE_VOID,
            $this->rules()->state(self::SENT, new DateTimeImmutable('2026-10-15 12:00:00'))
        );
    }

    public function testCleanupCutoffsMatchTheStateBoundaries(): void
    {
        $cutoffs = $this->rules()->cleanup_cutoffs(new DateTimeImmutable('2026-10-15 12:00:00'));

        // Rows with expires_datetime <= cutoff: expired at 12:00:00 on the 15th, void when the mail went out at
        // 12:00:00 on the 14th (expiry 12:10:00), see the state tests above.
        $this->assertSame('2026-10-15 12:00:00', $cutoffs['expired']);
        $this->assertSame('2026-10-14 12:10:00', $cutoffs['void']);
    }

    public function testCleanupCutoffForSendLimitsIsOneHourAfterTheLastMail(): void
    {
        // A limit whose last mail went out at 11:00:00 or earlier: its window (started no later) has ended at
        // 12:00:00 and its resend pause is long over, so it counts no more.
        $this->assertSame(
            '2026-10-15 11:00:00',
            $this->rules()->cleanup_cutoffs(new DateTimeImmutable('2026-10-15 12:00:00'))['limits']
        );
    }

    public function testExpiryOfANewBookingFreesItsSlot(): void
    {
        $this->assertTrue($this->rules()->expiry_frees_slot(\Email_verification_rules::PURPOSE_BOOKING));
    }

    public function testExpiryOfAnEmailChangeKeepsTheAppointment(): void
    {
        $this->assertFalse($this->rules()->expiry_frees_slot(\Email_verification_rules::PURPOSE_EMAIL_CHANGE));
        $this->assertSame([\Email_verification_rules::PURPOSE_BOOKING], $this->rules()->purposes_freeing_slot());
    }

    public function testRestoredAppointmentComesFromTheStoredBookingAndTheService(): void
    {
        $record = $this->rules()->booking_verification(
            self::APPOINTMENT,
            self::CUSTOMER,
            'token',
            new DateTimeImmutable('2026-10-14 12:00:00')
        );

        $appointment = $this->rules()->restored_appointment(
            $record,
            ['color' => '#aabbcc', 'location' => 'Salon Mitte'],
            'Booked'
        );

        $this->assertSame(
            [
                'id_services' => 3,
                'id_users_provider' => 2,
                'start_datetime' => '2026-11-02 10:00:00',
                'end_datetime' => '2026-11-02 11:00:00',
                'notes' => 'Bitte ruhig',
                'location' => 'Salon Mitte',
                'color' => '#aabbcc',
                'status' => 'Booked',
                'is_unavailability' => false
            ],
            $appointment
        );
    }

    private const LIMIT = [
        'email' => 'lena@example.com',
        'window_start_datetime' => '2026-10-14 12:00:00',
        'window_count' => 1,
        'last_sent_datetime' => '2026-10-14 12:00:00'
    ];

    public function testResendWithinSixtySecondsOfTheLastMailMustWait(): void
    {
        $this->assertSame(
            ['decision' => \Email_verification_rules::SEND_WAIT, 'wait_seconds' => 1],
            $this->rules()->send_decision(self::LIMIT, new DateTimeImmutable('2026-10-14 12:00:59'), true)
        );
    }

    public function testResendSixtySecondsAfterTheLastMailIsAllowed(): void
    {
        $this->assertSame(
            ['decision' => \Email_verification_rules::SEND_ALLOWED, 'wait_seconds' => 0],
            $this->rules()->send_decision(self::LIMIT, new DateTimeImmutable('2026-10-14 12:01:00'), true)
        );
    }

    public function testFirstMailToAnEmailIsAllowed(): void
    {
        $this->assertSame(
            ['decision' => \Email_verification_rules::SEND_ALLOWED, 'wait_seconds' => 0],
            $this->rules()->send_decision(null, new DateTimeImmutable('2026-10-14 12:00:00'), false)
        );
    }

    public function testSixthMailWithinTheHourIsRefusedUntilTheWindowEnds(): void
    {
        $limit = ['window_count' => 5, 'last_sent_datetime' => '2026-10-14 12:40:00'] + self::LIMIT;

        $this->assertSame(
            ['decision' => \Email_verification_rules::SEND_LIMIT, 'wait_seconds' => 600],
            $this->rules()->send_decision($limit, new DateTimeImmutable('2026-10-14 12:50:00'), true)
        );
    }

    public function testHourlyLimitAlsoRefusesANewVormerkungWithTheSameEmail(): void
    {
        $limit = ['window_count' => 5, 'last_sent_datetime' => '2026-10-14 12:40:00'] + self::LIMIT;

        $this->assertSame(
            \Email_verification_rules::SEND_LIMIT,
            $this->rules()->send_decision($limit, new DateTimeImmutable('2026-10-14 12:40:10'), false)['decision']
        );
    }

    public function testNewVormerkungDoesNotWaitForTheResendPause(): void
    {
        $this->assertSame(
            \Email_verification_rules::SEND_ALLOWED,
            $this->rules()->send_decision(self::LIMIT, new DateTimeImmutable('2026-10-14 12:00:10'), false)['decision']
        );
    }

    public function testLimitStartsAgainOneHourAfterTheFirstMailOfTheWindow(): void
    {
        $limit = ['window_count' => 5, 'last_sent_datetime' => '2026-10-14 12:40:00'] + self::LIMIT;

        $this->assertSame(
            ['decision' => \Email_verification_rules::SEND_ALLOWED, 'wait_seconds' => 0],
            $this->rules()->send_decision($limit, new DateTimeImmutable('2026-10-14 13:00:00'), true)
        );
    }

    public function testFirstMailStartsTheLimitWindowOfTheNormalizedEmail(): void
    {
        $this->assertSame(
            [
                'email' => 'lena@example.com',
                'window_start_datetime' => '2026-10-14 12:00:00',
                'window_count' => 1,
                'last_sent_datetime' => '2026-10-14 12:00:00'
            ],
            $this->rules()->limit_after_send(null, ' Lena@Example.com ', new DateTimeImmutable('2026-10-14 12:00:00'))
        );
    }

    public function testMailWithinTheWindowCountsUp(): void
    {
        $this->assertSame(
            [
                'email' => 'lena@example.com',
                'window_start_datetime' => '2026-10-14 12:00:00',
                'window_count' => 2,
                'last_sent_datetime' => '2026-10-14 12:05:00'
            ],
            $this->rules()->limit_after_send(
                self::LIMIT,
                'lena@example.com',
                new DateTimeImmutable('2026-10-14 12:05:00')
            )
        );
    }

    public function testMailAfterTheWindowStartsANewOne(): void
    {
        $limit = ['window_count' => 5, 'last_sent_datetime' => '2026-10-14 12:40:00'] + self::LIMIT;

        $this->assertSame(
            [
                'email' => 'lena@example.com',
                'window_start_datetime' => '2026-10-14 13:00:00',
                'window_count' => 1,
                'last_sent_datetime' => '2026-10-14 13:00:00'
            ],
            $this->rules()->limit_after_send($limit, 'lena@example.com', new DateTimeImmutable('2026-10-14 13:00:00'))
        );
    }

    public function testResendReplacesTheTokenAndHoldsTheSlotTenMinutesFromTheNewMail(): void
    {
        $record = $this->rules()->booking_verification(
            self::APPOINTMENT,
            self::CUSTOMER,
            'old-token',
            new DateTimeImmutable('2026-10-14 12:00:00')
        );

        $this->assertSame(
            [
                'token_hash' => hash('sha256', 'new-token'),
                'expires_datetime' => '2026-10-14 12:13:00',
                'last_sent_datetime' => '2026-10-14 12:03:00',
                'send_count' => 2
            ],
            $this->rules()->resent_verification($record, 'new-token', new DateTimeImmutable('2026-10-14 12:03:00'))
        );
    }

    public function testRescheduleWithAChangedEmailNeedsVerification(): void
    {
        $this->assertTrue(
            $this->rules()->requires_email_change_verification(true, 'anna@example.com', 'anna.neu@example.com', [])
        );
    }

    public function testRescheduleWithTheSameEmailInOtherCaseNeedsNoVerification(): void
    {
        $this->assertFalse(
            $this->rules()->requires_email_change_verification(true, 'anna@example.com', ' Anna@Example.COM ', [])
        );
    }

    public function testRescheduleWithAChangedEmailWithoutTheSettingNeedsNoVerification(): void
    {
        $this->assertFalse(
            $this->rules()->requires_email_change_verification(false, 'anna@example.com', 'anna.neu@example.com', [])
        );
    }

    public function testRescheduleWithAnEmptyEmailNeedsNoVerification(): void
    {
        $this->assertFalse($this->rules()->requires_email_change_verification(true, 'anna@example.com', ' ', []));
    }

    public function testChangedEmailVerifiedInThisSessionNeedsNoVerification(): void
    {
        $this->assertFalse(
            $this->rules()->requires_email_change_verification(true, 'anna@example.com', 'Anna.Neu@example.com', [
                'anna.neu@example.com'
            ])
        );
    }

    public function testEmailChangeVerificationStoresTheNewEmailOfTheAppointmentForTenMinutes(): void
    {
        $token = str_repeat('cd', 32);

        $record = $this->rules()->email_change_verification(
            ['id' => 41] + self::APPOINTMENT,
            ' Anna.Neu@Example.com ',
            $token,
            new DateTimeImmutable('2026-10-14 12:00:00')
        );

        $this->assertSame(
            [
                'id_appointments' => 41,
                'purpose' => 'email_change',
                'email' => 'anna.neu@example.com',
                'token_hash' => hash('sha256', $token),
                'expires_datetime' => '2026-10-14 12:10:00',
                'last_sent_datetime' => '2026-10-14 12:00:00',
                'send_count' => 1,
                'id_services' => 3,
                'id_users_provider' => 2,
                'start_datetime' => '2026-11-02 10:00:00',
                'end_datetime' => '2026-11-02 11:00:00',
                'notes' => 'Bitte ruhig',
                'customer_data' => '{"email":"Anna.Neu@Example.com"}'
            ],
            $record
        );
    }
}
