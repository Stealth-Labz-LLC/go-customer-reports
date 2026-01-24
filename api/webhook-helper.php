<?php
/**
 * Webhook Helper for Customer Reports Lead Submission
 * Sends leads to Stealth Labz portal
 */

$secretsFile = dirname(__DIR__) . '/config/secrets.php';
if (file_exists($secretsFile)) {
    $secrets = require $secretsFile;
    define('WEBHOOK_URL', $secrets['webhook_url'] ?? '');
} else {
    define('WEBHOOK_URL', '');
}

/**
 * Format phone number to E.164 format
 */
function formatPhoneE164($phone) {
    $phone = preg_replace('/[^0-9]/', '', $phone);

    if (strlen($phone) === 11 && substr($phone, 0, 1) === '1') {
        return '+' . $phone;
    }

    if (strlen($phone) === 10) {
        $phone = '1' . $phone;
    }

    if (substr($phone, 0, 1) !== '1') {
        $phone = '1' . $phone;
    }

    return '+' . $phone;
}

/**
 * Format date to ISO 8601 format
 */
function formatDateISO8601($date = null) {
    if ($date) {
        $dateTime = new DateTime($date);
    } else {
        $dateTime = new DateTime();
    }
    return $dateTime->format('c');
}

/**
 * Send lead to Stealth Portal webhook
 * @param array $data - Form data
 * @param string $campaign - Campaign identifier (eb, ee25, qr, cr, etc.)
 * @param array $extraFields - Additional fields to include in notes
 */
function sendToWebhook($data, $campaign, $extraFields = []) {
    if (empty(WEBHOOK_URL)) {
        return [
            'success' => false,
            'http_code' => 0,
            'response' => '',
            'error' => 'Webhook URL not configured',
            'payload' => []
        ];
    }

    // Build notes from campaign and extra fields
    $notesArray = ["Campaign: " . $campaign];

    foreach ($extraFields as $key => $value) {
        if (!empty($value) && $value !== 'N/A') {
            $notesArray[] = ucfirst(str_replace('_', ' ', $key)) . ": " . $value;
        }
    }

    if (!empty($data['aff_id'])) {
        $notesArray[] = "Affiliate ID: " . $data['aff_id'];
    }
    if (!empty($data['aff_sub'])) {
        $notesArray[] = "Aff Sub: " . $data['aff_sub'];
    }
    if (!empty($data['source'])) {
        $notesArray[] = "Source: " . $data['source'];
    }

    $notes = implode("; ", $notesArray);

    // Parse name into first/last
    $fullName = trim($data['name'] ?? '');
    $nameParts = explode(' ', $fullName, 2);
    $firstName = $nameParts[0] ?? '';
    $lastName = $nameParts[1] ?? '';

    // Build webhook payload
    $payload = [
        'url' => !empty($data['optinurl']) ? $data['optinurl'] : ($_SERVER['HTTP_REFERER'] ?? ''),
        'first_name' => $firstName,
        'last_name' => $lastName,
        'phone' => formatPhoneE164($data['phone'] ?? ''),
        'email' => trim($data['email'] ?? ''),
        'country' => $data['country'] ?? 'US',
        'zip' => $data['zip'] ?? '',
        'interest_tags' => [$campaign],
        'preferred_contact' => 'both',
        'language' => $data['language'] ?? 'en',
        'timezone' => $data['timezone'] ?? 'America/New_York',
        'signup_date' => formatDateISO8601(),
        'consent' => !empty($data['consent']),
        'consent_timestamp' => formatDateISO8601(),
        'consent_source' => 'signup_form',
        'consent_text' => 'I agree to the Terms of Service and Privacy Policy.',
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
        'referrer' => $_SERVER['HTTP_REFERER'] ?? '',
        'notes' => $notes
    ];

    // Remove empty optional fields
    $requiredFields = ['url', 'first_name', 'last_name', 'phone', 'email', 'country', 'consent'];
    foreach ($payload as $key => $value) {
        if (empty($value) && !in_array($key, $requiredFields)) {
            unset($payload[$key]);
        }
    }

    // Send to webhook
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => WEBHOOK_URL,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json'
        ],
    ]);

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $error = curl_error($curl);
    curl_close($curl);

    return [
        'success' => ($httpCode >= 200 && $httpCode < 300),
        'http_code' => $httpCode,
        'response' => $response,
        'error' => $error,
        'payload' => $payload
    ];
}

/**
 * Validate required fields before sending
 */
function validateRequiredFields($data) {
    $errors = [];

    $name = trim($data['name'] ?? '');
    $phone = $data['phone'] ?? '';
    $email = trim($data['email'] ?? '');

    if (empty($name)) {
        $errors[] = 'Name is required';
    }
    if (empty($phone)) {
        $errors[] = 'Phone number is required';
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required';
    }

    return [
        'valid' => empty($errors),
        'errors' => $errors
    ];
}
