<?php

namespace App\Core;

/**
 * Security Helper Class
 * Handles CSRF protection, input sanitization, and validation
 */
class Security
{
    /**
     * Generate a CSRF token and store in session
     */
    public static function generateCsrfToken(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        $_SESSION['csrf_token_time'] = time();

        return $token;
    }

    /**
     * Get current CSRF token or generate new one
     */
    public static function getCsrfToken(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['csrf_token'])) {
            return self::generateCsrfToken();
        }

        return $_SESSION['csrf_token'];
    }

    /**
     * Validate CSRF token from request
     */
    public static function validateCsrfToken(string $token, int $maxAge = 3600): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['csrf_token']) || !isset($_SESSION['csrf_token_time'])) {
            return false;
        }

        if (!hash_equals($_SESSION['csrf_token'], $token)) {
            return false;
        }

        if (time() - $_SESSION['csrf_token_time'] > $maxAge) {
            return false;
        }

        return true;
    }

    /**
     * Generate CSRF input field HTML
     */
    public static function csrfField(): string
    {
        $token = self::getCsrfToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }

    /**
     * Sanitize a string for safe output/storage
     */
    public static function sanitizeString(string $input): string
    {
        $input = str_replace(chr(0), '', $input);
        $input = trim($input);
        $input = strip_tags($input);
        return $input;
    }

    /**
     * Sanitize an email address
     */
    public static function sanitizeEmail(string $email): string|false
    {
        $email = trim($email);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        }

        return false;
    }

    /**
     * Sanitize a phone number (keep only digits)
     */
    public static function sanitizePhone(string $phone): string
    {
        return preg_replace('/[^0-9]/', '', $phone);
    }

    /**
     * Sanitize a zip code
     */
    public static function sanitizeZip(string $zip): string
    {
        $zip = preg_replace('/[^0-9\-]/', '', $zip);

        if (preg_match('/^\d{5}(-\d{4})?$/', $zip)) {
            return $zip;
        }

        return preg_replace('/[^0-9]/', '', $zip);
    }

    /**
     * Sanitize all fields in an array
     */
    public static function sanitizeArray(array $data, array $rules = []): array
    {
        $sanitized = [];

        foreach ($data as $key => $value) {
            if (!is_string($value)) {
                $sanitized[$key] = $value;
                continue;
            }

            if (isset($rules[$key])) {
                switch ($rules[$key]) {
                    case 'email':
                        $sanitized[$key] = self::sanitizeEmail($value) ?: '';
                        break;
                    case 'phone':
                        $sanitized[$key] = self::sanitizePhone($value);
                        break;
                    case 'zip':
                        $sanitized[$key] = self::sanitizeZip($value);
                        break;
                    default:
                        $sanitized[$key] = self::sanitizeString($value);
                }
            } else {
                $sanitized[$key] = self::sanitizeString($value);
            }
        }

        return $sanitized;
    }

    /**
     * Validate that a value is in an allowed list
     */
    public static function validateWhitelist(string $value, array $allowed, ?string $default = null): ?string
    {
        $value = strtolower(trim($value));

        if (in_array($value, $allowed)) {
            return $value;
        }

        return $default;
    }

    /**
     * Get allowed origin for CORS
     */
    public static function getAllowedOrigin(): ?string
    {
        $allowedOrigins = [
            'https://gocustomerreports.com',
            'https://www.gocustomerreports.com',
            'http://localhost:8080',
            'http://localhost',
        ];

        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

        if (in_array($origin, $allowedOrigins)) {
            return $origin;
        }

        if (defined('IS_LOCAL') && IS_LOCAL) {
            return $origin ?: '*';
        }

        return null;
    }
}
