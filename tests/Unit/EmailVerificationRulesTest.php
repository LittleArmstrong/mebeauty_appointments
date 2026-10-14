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
}
