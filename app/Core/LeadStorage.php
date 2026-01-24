<?php
/**
 * Lead Storage
 * Backs up leads to JSON files as a safety net
 */

namespace App\Core;

class LeadStorage
{
    private static string $storageDir = '';

    private static function init(): void
    {
        if (empty(self::$storageDir)) {
            self::$storageDir = defined('ROOT_PATH')
                ? ROOT_PATH . '/storage/leads'
                : dirname(__DIR__, 2) . '/storage/leads';

            if (!is_dir(self::$storageDir)) {
                mkdir(self::$storageDir, 0755, true);
            }
        }
    }

    /**
     * Save a lead to storage
     * @return string|false The lead ID on success, false on failure
     */
    public static function save(array $leadData, string $campaign = 'general'): string|false
    {
        self::init();

        $leadId = self::generateLeadId();
        $timestamp = date('Y-m-d H:i:s');
        $dateFolder = date('Y-m');

        $monthDir = self::$storageDir . "/{$dateFolder}";
        if (!is_dir($monthDir)) {
            mkdir($monthDir, 0755, true);
        }

        $lead = [
            'id' => $leadId,
            'campaign' => $campaign,
            'created_at' => $timestamp,
            'webhook_sent' => false,
            'webhook_status' => null,
            'data' => $leadData,
            'meta' => [
                'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
                'referer' => $_SERVER['HTTP_REFERER'] ?? '',
            ],
        ];

        $dailyFile = $monthDir . "/" . date('Y-m-d') . ".jsonl";
        $line = json_encode($lead, JSON_UNESCAPED_SLASHES) . PHP_EOL;

        if (file_put_contents($dailyFile, $line, FILE_APPEND | LOCK_EX) !== false) {
            return $leadId;
        }

        return false;
    }

    /**
     * Update lead status after webhook attempt
     */
    public static function updateWebhookStatus(string $leadId, bool $success, array $webhookResult = []): void
    {
        self::init();

        $statusFile = self::$storageDir . "/webhook-status.jsonl";

        $status = [
            'lead_id' => $leadId,
            'timestamp' => date('Y-m-d H:i:s'),
            'success' => $success,
            'http_code' => $webhookResult['http_code'] ?? null,
            'error' => $webhookResult['error'] ?? null,
        ];

        $line = json_encode($status, JSON_UNESCAPED_SLASHES) . PHP_EOL;
        file_put_contents($statusFile, $line, FILE_APPEND | LOCK_EX);
    }

    private static function generateLeadId(): string
    {
        return date('Ymd') . '-' . bin2hex(random_bytes(6));
    }

    /**
     * Get failed leads (for retry processing)
     */
    public static function getFailedLeads(): array
    {
        self::init();

        $statusFile = self::$storageDir . "/webhook-status.jsonl";
        if (!file_exists($statusFile)) {
            return [];
        }

        $failed = [];
        $succeeded = [];

        $lines = file($statusFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $status = json_decode($line, true);
            if ($status) {
                if ($status['success']) {
                    $succeeded[$status['lead_id']] = true;
                } else {
                    $failed[$status['lead_id']] = $status;
                }
            }
        }

        return array_diff_key($failed, $succeeded);
    }
}
