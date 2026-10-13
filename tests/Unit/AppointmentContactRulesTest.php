<?php

namespace Tests\Unit;

use Tests\TestCase;

class AppointmentContactRulesTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        if (!class_exists('Appointment_contact_rules')) {
            require_once APPPATH . 'libraries/Appointment_contact_rules.php';
        }
    }

    private function rules(): \Appointment_contact_rules
    {
        return new \Appointment_contact_rules();
    }

    private const ANNA = [
        'id' => 7,
        'first_name' => 'Anna',
        'last_name' => 'Alt',
        'email' => 'anna@example.com',
        'phone_number' => '0221 111',
        'address' => 'Ringstraße 1',
        'city' => 'Köln',
        'zip_code' => '50667',
        'timezone' => 'Europe/Berlin',
        'language' => 'german'
    ];

    private const LENA_INPUT = [
        'first_name' => 'Lena',
        'last_name' => 'Neu',
        'email' => 'anna@example.com',
        'phone_number' => '0170 222',
        'timezone' => 'UTC',
        'language' => 'english'
    ];

    // Customer resolution

    public function testNewBookingWithUnknownEmailCreatesACustomer(): void
    {
        $this->assertNull($this->rules()->resolve_customer_id(null, 0));
    }

    public function testNewBookingWithKnownEmailUsesTheExistingCustomer(): void
    {
        $this->assertSame(7, $this->rules()->resolve_customer_id(null, 7));
    }

    public function testRescheduleKeepsTheCustomerOfTheOriginalAppointment(): void
    {
        $this->assertSame(7, $this->rules()->resolve_customer_id(['id_users_customer' => '7'], 9));
    }

    // Customer record update

    public function testNewCustomerGetsAllFields(): void
    {
        $record = $this->rules()->customer_record(self::LENA_INPUT, null);

        $this->assertSame(self::LENA_INPUT, $record);
    }

    public function testExistingCustomerTakesOverNonEmptyFields(): void
    {
        $record = $this->rules()->customer_record(self::LENA_INPUT, self::ANNA);

        $this->assertSame(7, $record['id']);
        $this->assertSame('Lena', $record['first_name']);
        $this->assertSame('0170 222', $record['phone_number']);
        $this->assertSame('Ringstraße 1', $record['address']);
        $this->assertSame('UTC', $record['timezone']);
        $this->assertSame('english', $record['language']);
    }

    public function testEmptyFieldDoesNotDeleteStoredData(): void
    {
        $input = ['phone_number' => '', 'address' => '   ', 'city' => null] + self::LENA_INPUT;

        $record = $this->rules()->customer_record($input, self::ANNA);

        $this->assertSame('0221 111', $record['phone_number']);
        $this->assertSame('Ringstraße 1', $record['address']);
        $this->assertSame('Köln', $record['city']);
    }

    public function testRescheduleTakesOverAFreeNewEmail(): void
    {
        $input = ['email' => 'anna-neu@example.com'] + self::LENA_INPUT;

        $record = $this->rules()->customer_record($input, self::ANNA, null);

        $this->assertSame('anna-neu@example.com', $record['email']);
    }

    public function testRescheduleKeepsTheEmailWhenAnotherCustomerHasIt(): void
    {
        $input = ['email' => 'bernd@example.com'] + self::LENA_INPUT;

        $record = $this->rules()->customer_record($input, self::ANNA, 12);

        $this->assertSame('anna@example.com', $record['email']);
        $this->assertSame(7, $record['id']);
    }

    public function testEmailOwnedByTheSameCustomerIsNotABlocker(): void
    {
        $input = ['email' => 'ANNA@example.com'] + self::LENA_INPUT;

        $record = $this->rules()->customer_record($input, self::ANNA, 7);

        $this->assertSame('ANNA@example.com', $record['email']);
    }

    // Appointment contact

    public function testContactContainsTheFourFieldsAsEntered(): void
    {
        $this->assertSame(
            [
                'first_name' => 'Lena',
                'last_name' => 'Neu',
                'email' => 'anna@example.com',
                'phone_number' => '0170 222'
            ],
            $this->rules()->contact(self::LENA_INPUT)
        );
    }

    public function testContactStoresMissingOrEmptyFieldsAsNull(): void
    {
        $contact = $this->rules()->contact(['first_name' => ' Lena ', 'phone_number' => '']);

        $this->assertSame(
            ['first_name' => 'Lena', 'last_name' => null, 'email' => null, 'phone_number' => null],
            $contact
        );
    }

    public function testDisplayCustomerPrefersTheAppointmentContact(): void
    {
        $contact = [
            'first_name' => 'Lena',
            'last_name' => 'Neu',
            'email' => 'lena@example.com',
            'phone_number' => null
        ];

        $customer = $this->rules()->display_customer(self::ANNA, $contact);

        $this->assertSame('Lena', $customer['first_name']);
        $this->assertSame('lena@example.com', $customer['email']);
        $this->assertNull($customer['phone_number']);
        $this->assertSame('Ringstraße 1', $customer['address']);
        $this->assertSame(7, $customer['id']);
    }

    public function testDisplayCustomerFallsBackToTheRecordWithoutContact(): void
    {
        $this->assertSame(self::ANNA, $this->rules()->display_customer(self::ANNA, null));
    }

    // Backend dialog save

    public function testBackendSaveOfANewAppointmentStoresTheDialogInput(): void
    {
        $result = $this->rules()->backend_save(self::LENA_INPUT, null, null);

        $this->assertSame(self::LENA_INPUT, $result['customer']);
        $this->assertSame('Lena', $result['contact']['first_name']);
    }

    public function testBackendSaveChangesOnlyEditedContactFields(): void
    {
        $stored = [
            'first_name' => 'Lena',
            'last_name' => 'Neu',
            'email' => 'anna@example.com',
            'phone_number' => '0170 222'
        ];
        $input = ['phone_number' => '0170 223', 'address' => 'Neu 2'] + $stored;

        $result = $this->rules()->backend_save($input, self::ANNA, $stored);

        $this->assertSame('Anna', $result['customer']['first_name']);
        $this->assertSame('0170 223', $result['customer']['phone_number']);
        $this->assertSame('Neu 2', $result['customer']['address']);
        $this->assertEquals(['phone_number' => '0170 223'] + $stored, $result['contact']);
    }

    public function testBackendSaveWithoutChangesKeepsTheContact(): void
    {
        $stored = [
            'first_name' => 'Lena',
            'last_name' => 'Neu',
            'email' => 'anna@example.com',
            'phone_number' => '0170 222'
        ];

        $result = $this->rules()->backend_save($stored, self::ANNA, $stored);

        $this->assertNull($result['contact']);
        $this->assertSame('Anna', $result['customer']['first_name']);
    }

    public function testBackendSaveOfAnOlderAppointmentComparesWithTheRecord(): void
    {
        $input = ['last_name' => 'Neu'] + self::ANNA;

        $result = $this->rules()->backend_save($input, self::ANNA, null);

        $this->assertSame('Neu', $result['customer']['last_name']);
        $this->assertSame(
            ['first_name' => 'Anna', 'last_name' => 'Neu', 'email' => 'anna@example.com', 'phone_number' => '0221 111'],
            $result['contact']
        );
    }

    // Backend diff

    public function testChangedFieldsOnlyContainsChangedContactFields(): void
    {
        $stored = [
            'first_name' => 'Lena',
            'last_name' => 'Neu',
            'email' => 'anna@example.com',
            'phone_number' => '0170 222'
        ];
        $input = ['phone_number' => '0170 223', 'address' => 'Neu 2'] + $stored;

        $this->assertSame(['phone_number' => '0170 223'], $this->rules()->changed_fields($stored, $input));
    }

    public function testChangedFieldsIgnoresWhitespaceAndNullVersusEmpty(): void
    {
        $stored = ['first_name' => 'Lena', 'last_name' => 'Neu', 'email' => null, 'phone_number' => '0170 222'];
        $input = ['first_name' => ' Lena', 'last_name' => 'Neu', 'email' => '', 'phone_number' => '0170 222'];

        $this->assertSame([], $this->rules()->changed_fields($stored, $input));
    }

    public function testChangedFieldsIgnoresFieldsMissingInTheInput(): void
    {
        $stored = ['first_name' => 'Lena', 'last_name' => 'Neu', 'email' => null, 'phone_number' => '0170 222'];

        $this->assertSame(['last_name' => 'Alt'], $this->rules()->changed_fields($stored, ['last_name' => 'Alt']));
    }

    // Google contact block

    private const CONTACT = [
        'first_name' => 'Lena',
        'last_name' => 'Neu',
        'email' => 'lena@example.com',
        'phone_number' => '0170 / 222-33'
    ];

    public function testEventDescriptionStartsWithTheContactBlock(): void
    {
        $description = $this->rules()->event_description(self::CONTACT, 'Bitte Allergie beachten');

        $this->assertSame(
            "--- Kontakt ---\nLena Neu\nTel: 017022233\nE-Mail: lena@example.com\n---\n\nBitte Allergie beachten",
            $description
        );
    }

    public function testPhoneNumberKeepsALeadingPlusOnly(): void
    {
        $contact = ['phone_number' => '+49 (170) 222+33'] + self::CONTACT;

        $this->assertStringContainsString("Tel: +4917022233\n", $this->rules()->event_description($contact, ''));
    }

    public function testBlockOmitsEmptyLines(): void
    {
        $contact = ['first_name' => null, 'last_name' => 'Neu', 'email' => null, 'phone_number' => ''];

        $this->assertSame("--- Kontakt ---\nNeu\n---", $this->rules()->event_description($contact, null));
    }

    public function testWithoutContactTheDescriptionIsTheNotes(): void
    {
        $this->assertSame('Notiz', $this->rules()->event_description(null, 'Notiz'));
        $this->assertSame('', $this->rules()->event_description(['first_name' => '', 'last_name' => null], null));
    }

    public function testStripRemovesTheBlockAndKeepsTheNotes(): void
    {
        $rules = $this->rules();

        $this->assertSame('Notiz', $rules->strip_contact_block($rules->event_description(self::CONTACT, 'Notiz')));
        $this->assertSame(
            "Zeile 1\nZeile 2",
            $rules->strip_contact_block($rules->event_description(self::CONTACT, "Zeile 1\nZeile 2"))
        );
    }

    public function testStripOfABlockWithoutNotesIsEmpty(): void
    {
        $rules = $this->rules();

        $this->assertSame('', $rules->strip_contact_block($rules->event_description(self::CONTACT, '')));
    }

    public function testStripHandlesTheHtmlThatGoogleWritesAfterAnEdit(): void
    {
        $description =
            '--- Kontakt ---<br>Lena Neu<br>Tel: <a href="tel:017022233">017022233</a><br>E-Mail: lena@example.com<br>---<br><br>Notiz';

        $this->assertSame('Notiz', $this->rules()->strip_contact_block($description));
    }

    public function testNotesWithoutBlockStayUnchanged(): void
    {
        $rules = $this->rules();

        $this->assertSame(' Notiz mit --- Strichen ---', $rules->strip_contact_block(' Notiz mit --- Strichen ---'));
        $this->assertSame('', $rules->strip_contact_block(null));
        $this->assertSame('', $rules->strip_contact_block(''));
    }
}
