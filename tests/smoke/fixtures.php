<?php
/**
 * Test data helper for the smoke tests (tests/smoke/*-smoke.sh).
 *
 * Boots CodeIgniter via the empty Test controller (the same CLI/bootstrap seam PHPUnit uses).
 *
 * Usage (inside the php-fpm container):
 *
 *   APP_ENV=testing php tests/smoke/fixtures.php setup
 *       Creates two customers with one future appointment each and prints JSON with the IDs, hashes,
 *       provider/service and free target slots.
 *   APP_ENV=testing php tests/smoke/fixtures.php get <appointment-id>
 *       Prints the stored appointment (JSON, or "null" when it does not exist).
 *   APP_ENV=testing php tests/smoke/fixtures.php appointment-by-hash <hash>
 *       Prints the appointment with that manage link hash (JSON, or "null").
 *   APP_ENV=testing php tests/smoke/fixtures.php get-by-customer <customer-id> <start Y-m-d H:i:s>
 *       Prints the customer's appointment with that start (JSON, or "null").
 *   APP_ENV=testing php tests/smoke/fixtures.php customer <customer-id> [<field> <value>]
 *       Prints the stored customer (JSON); with field and value, changes that field first.
 *   APP_ENV=testing php tests/smoke/fixtures.php contact <appointment-id>
 *       Prints the stored appointment contact (JSON, or "null" when the appointment has none).
 *   APP_ENV=testing php tests/smoke/fixtures.php setting <name> [<value>]
 *       Prints the current value of a setting and optionally replaces it.
 *   APP_ENV=testing php tests/smoke/fixtures.php lang <key>
 *       Prints a translation (to compare error messages).
 *   APP_ENV=testing php tests/smoke/fixtures.php cleanup <appointment-ids> <customer-ids>
 *       Deletes the given comma-separated appointments and customers.
 *   APP_ENV=testing php tests/smoke/fixtures.php verification-by-email <email>
 *       Prints the newest email verification for that email (JSON, or "null"), spec 0018.
 *   APP_ENV=testing php tests/smoke/fixtures.php verification-token <appointment-id|email> <token> [<minutes-back>]
 *       Gives the verification of the appointment (or the newest one of the email, e.g. after its expired
 *       Vormerkung was deleted) a known token (the smoke test reads no mails) and, with minutes-back, moves its
 *       expiry, last mail and creation time that many minutes into the past. Prints the verification (JSON, or
 *       "null" when there is none).
 *   APP_ENV=testing php tests/smoke/fixtures.php cleanup-verifications
 *       Runs the email verification part of `console cleanup` (Cleanup::cleanup_email_verifications()).
 *   APP_ENV=testing php tests/smoke/fixtures.php purge
 *       Deletes every smoke customer (email smoke-…@example.com) with their appointments and every email
 *       verification of a smoke email with its Vormerkung, e.g. left over by an aborted run.
 */

$_SERVER['argv'] = ['fixtures', 'test'];

$command = $argv[1] ?? '';
$arguments = array_slice($argv, 2);

require __DIR__ . '/../../index.php';

$CI = &get_instance();
$CI->load->model('appointments_model');
$CI->load->model('customers_model');
$CI->load->model('appointment_contacts_model');
$CI->load->model('appointment_verifications_model');
$CI->load->model('providers_model');
$CI->load->model('services_model');
$CI->load->library('availability');

/**
 * Find a provider/service pair and a date with at least three free slots.
 */
function smoke_find_slots(): array
{
    $CI = &get_instance();

    foreach ($CI->providers_model->get() as $provider) {
        foreach ($provider['services'] ?? [] as $service_id) {
            $service = $CI->services_model->find((int) $service_id);

            for ($days = 10; $days <= 40; $days++) {
                $date = date('Y-m-d', strtotime('+' . $days . ' days'));

                $hours = $CI->availability->get_available_hours($date, $service, $provider);

                // Keep slots at least one service duration apart so they do not overlap.
                $picked = [];

                foreach ($hours as $hour) {
                    $start = strtotime($date . ' ' . $hour);

                    if ($picked === [] || $start >= strtotime($date . ' ' . end($picked)) + $service['duration'] * 60) {
                        $picked[] = $hour;
                    }
                }

                if (count($picked) >= 3) {
                    return [$provider, $service, $date, $picked];
                }
            }
        }
    }

    throw new RuntimeException('No provider/service with three free slots in the next 40 days.');
}

/**
 * Create a customer and one appointment for them.
 */
function smoke_create_booking(array $provider, array $service, string $date, string $hour, string $label): array
{
    $CI = &get_instance();

    $suffix = bin2hex(random_bytes(4));

    $customer_id = $CI->customers_model->save([
        'first_name' => 'Smoke',
        'last_name' => $label,
        'email' => 'smoke-' . strtolower($label) . '-' . $suffix . '@example.com',
        'phone_number' => '0000',
        'timezone' => $provider['timezone'],
        'language' => 'english'
    ]);

    $start = $date . ' ' . $hour . ':00';
    $end = date('Y-m-d H:i:s', strtotime($start) + $service['duration'] * 60);

    $appointment_id = $CI->appointments_model->save([
        'start_datetime' => $start,
        'end_datetime' => $end,
        'id_users_provider' => $provider['id'],
        'id_users_customer' => $customer_id,
        'id_services' => $service['id'],
        'is_unavailability' => false,
        'notes' => 'smoke ' . $label
    ]);

    $appointment = $CI->appointments_model->find($appointment_id);

    return [
        'id' => (int) $appointment_id,
        'hash' => $appointment['hash'],
        'customer_id' => (int) $customer_id,
        'start' => $start
    ];
}

switch ($command) {
    case 'setup':
        [$provider, $service, $date, $hours] = smoke_find_slots();

        echo json_encode([
            'provider_id' => (int) $provider['id'],
            'service_id' => (int) $service['id'],
            'date' => $date,
            'victim' => smoke_create_booking($provider, $service, $date, $hours[0], 'Victim'),
            'owner' => smoke_create_booking($provider, $service, $date, $hours[1], 'Owner'),
            'target_hour' => $hours[2]
        ]) . PHP_EOL;
        break;

    case 'get':
        $rows = $CI->appointments_model->get(['id' => (int) ($arguments[0] ?? 0)]);
        echo json_encode($rows[0] ?? null) . PHP_EOL;
        break;

    case 'appointment-by-hash':
        $rows = $CI->appointments_model->get(['hash' => (string) ($arguments[0] ?? '')]);
        echo json_encode($rows[0] ?? null) . PHP_EOL;
        break;

    case 'get-by-customer':
        $rows = $CI->appointments_model->get([
            'id_users_customer' => (int) ($arguments[0] ?? 0),
            // The start arrives as two arguments (date, time) through the shell.
            'start_datetime' => implode(' ', array_slice($arguments, 1))
        ]);
        echo json_encode($rows[0] ?? null) . PHP_EOL;
        break;

    case 'customer':
        if (array_key_exists(2, $arguments)) {
            $CI->db->update('users', [$arguments[1] => $arguments[2]], ['id' => (int) $arguments[0]]);
        }

        echo json_encode($CI->customers_model->find((int) ($arguments[0] ?? 0))) . PHP_EOL;
        break;

    case 'contact':
        echo json_encode($CI->appointment_contacts_model->find_by_appointment((int) ($arguments[0] ?? 0))) . PHP_EOL;
        break;

    case 'setting':
        $name = $arguments[0] ?? '';
        echo (string) setting($name) . PHP_EOL;

        if (array_key_exists(1, $arguments)) {
            setting([$name => $arguments[1]]);
        }
        break;

    case 'lang':
        echo lang($arguments[0] ?? '') . PHP_EOL;
        break;

    case 'cleanup':
        foreach (array_filter(explode(',', $arguments[0] ?? '')) as $id) {
            $CI->db->delete('appointments', ['id' => (int) $id]);
        }

        foreach (array_filter(explode(',', $arguments[1] ?? '')) as $id) {
            $CI->db->delete('users', ['id' => (int) $id]);
        }
        break;

    case 'verification-by-email':
        echo json_encode($CI->appointment_verifications_model->find_newest_by_email($arguments[0] ?? '')) . PHP_EOL;
        break;

    case 'verification-token':
        $key = $arguments[0] ?? '';
        $minutes_back = (int) ($arguments[2] ?? 0);

        $verification = str_contains($key, '@')
            ? $CI->appointment_verifications_model->find_newest_by_email($key)
            : $CI->appointment_verifications_model->find_by_appointment((int) $key);

        if (!$verification) {
            echo 'null' . PHP_EOL;
            break;
        }

        $CI->db->set('token_hash', hash('sha256', $arguments[1] ?? ''));

        foreach (['expires_datetime', 'last_sent_datetime', 'create_datetime'] as $field) {
            $CI->db->set($field, 'DATE_SUB(`' . $field . '`, INTERVAL ' . $minutes_back . ' MINUTE)', false);
        }

        $CI->db->where('id', $verification['id'])->update('appointment_verifications');

        echo json_encode(
            $CI->appointment_verifications_model->find_by_token_hash(hash('sha256', $arguments[1] ?? ''))
        ) . PHP_EOL;
        break;

    case 'cleanup-verifications':
        $CI->load->library('cleanup');
        $CI->cleanup->cleanup_email_verifications();
        break;

    case 'purge':
        $smoke_verifications = $CI->db
            ->select('id, id_appointments')
            ->like('email', 'smoke-', 'after')
            ->like('email', '@example.com', 'before')
            ->get('appointment_verifications')
            ->result_array();

        foreach ($smoke_verifications as $verification) {
            if ($verification['id_appointments']) {
                $CI->db->delete('appointments', [
                    'id' => (int) $verification['id_appointments'],
                    'id_users_customer' => null
                ]);
            }

            $CI->db->delete('appointment_verifications', ['id' => (int) $verification['id']]);
        }

        $customer_role_id = $CI->customers_model->get_customer_role_id();

        $smoke_customers = $CI->db
            ->select('id')
            ->like('email', 'smoke-', 'after')
            ->like('email', '@example.com', 'before')
            ->where('id_roles', $customer_role_id)
            ->get('users')
            ->result_array();

        foreach ($smoke_customers as $customer) {
            $CI->db->delete('appointments', ['id_users_customer' => (int) $customer['id']]);
            $CI->db->delete('users', ['id' => (int) $customer['id']]);
        }

        echo count($smoke_customers) . PHP_EOL;
        break;

    default:
        fwrite(
            STDERR,
            'Usage: fixtures.php setup|get|appointment-by-hash|get-by-customer|customer|contact|setting|lang|cleanup|' .
                'verification-by-email|verification-token|cleanup-verifications|purge' .
                PHP_EOL
        );
        exit(2);
}
