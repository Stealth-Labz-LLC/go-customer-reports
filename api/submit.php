<?php
/**
 * Customer Reports Lead Submission API
 * Receives form data, stores locally, sends to Stealth Portal webhook
 */
session_start();

$rootPath = dirname(__DIR__);

// Load environment
if (file_exists($rootPath . '/config/environment.php')) {
    require_once $rootPath . '/config/environment.php';
}

// Load Core classes
require_once $rootPath . '/app/Core/Security.php';
require_once $rootPath . '/app/Core/LeadStorage.php';
require_once $rootPath . '/app/Core/Logger.php';

use App\Core\Security;
use App\Core\LeadStorage;
use App\Core\Logger;

header('Content-Type: application/json');

// CORS
$allowedOrigin = Security::getAllowedOrigin();
if ($allowedOrigin) {
    header('Access-Control-Allow-Origin: ' . $allowedOrigin);
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Access-Control-Allow-Credentials: true');
}

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// POST only
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// CSRF validation (skip in local dev)
$csrfToken = $_POST['csrf_token'] ?? '';
if (!defined('IS_LOCAL') || !IS_LOCAL) {
    if (!Security::validateCsrfToken($csrfToken)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Invalid security token. Please refresh and try again.']);
        exit;
    }
}

require_once __DIR__ . '/webhook-helper.php';

// Sanitize input
$sanitizationRules = [
    'email' => 'email',
    'phone' => 'phone',
    'zip' => 'zip',
];
$data = Security::sanitizeArray($_POST, $sanitizationRules);

// Validate required fields
$validation = validateRequiredFields($data);
if (!$validation['valid']) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => implode(', ', $validation['errors'])]);
    exit;
}

// Determine campaign from form data
$allowedCampaigns = ['eb', 'ee25', 'qr', 'cr', 'sc', 'ss', 'general'];
$campaign = Security::validateWhitelist($data['campaign'] ?? 'general', $allowedCampaigns, 'general');

// Build extra fields for notes
$extraFields = [];
foreach (['quiz_result', 'product', 'interest', 'urgency', 'zip'] as $field) {
    if (!empty($data[$field])) {
        $extraFields[$field] = $data[$field];
    }
}

// Add referrer URL
$referer = $_SERVER['HTTP_REFERER'] ?? '';
$data['optinurl'] = filter_var($referer, FILTER_SANITIZE_URL);

// STEP 1: Save lead locally (never lose data)
$leadId = LeadStorage::save([
    'name' => $data['name'] ?? '',
    'email' => $data['email'] ?? '',
    'phone' => $data['phone'] ?? '',
    'zip' => $data['zip'] ?? '',
    'campaign' => $campaign,
    'product' => $data['product'] ?? '',
    'quiz_result' => $data['quiz_result'] ?? '',
    'interest' => $data['interest'] ?? '',
    'source' => $data['source'] ?? 'direct',
    'aff_id' => $data['aff_id'] ?? '',
    'aff_sub' => $data['aff_sub'] ?? '',
    'optinurl' => $data['optinurl'] ?? '',
], $campaign);

if ($leadId) {
    Logger::info('Lead saved', ['lead_id' => $leadId, 'campaign' => $campaign]);
} else {
    Logger::error('Failed to save lead', ['email' => $data['email'] ?? 'unknown']);
}

// STEP 2: Send to Stealth Portal
$webhookResult = sendToWebhook($data, $campaign, $extraFields);

// STEP 3: Update webhook status
if ($leadId) {
    LeadStorage::updateWebhookStatus($leadId, $webhookResult['success'], $webhookResult);
}

// Always return success to user (lead is safe locally regardless)
if ($webhookResult['success']) {
    Logger::info('Webhook success', ['lead_id' => $leadId, 'http_code' => $webhookResult['http_code']]);
} else {
    Logger::error('Webhook failed', [
        'lead_id' => $leadId,
        'http_code' => $webhookResult['http_code'] ?? null,
        'error' => $webhookResult['error'] ?? 'Unknown error'
    ]);
}

echo json_encode(['success' => true, 'message' => 'Submitted successfully']);
