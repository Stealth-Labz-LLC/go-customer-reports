<?php
/**
 * Simple File Logger
 */

namespace App\Core;

class Logger
{
    private static string $logDir = '';

    private static function init(): void
    {
        if (empty(self::$logDir)) {
            self::$logDir = defined('ROOT_PATH')
                ? ROOT_PATH . '/storage/logs'
                : dirname(__DIR__, 2) . '/storage/logs';

            if (!is_dir(self::$logDir)) {
                mkdir(self::$logDir, 0755, true);
            }
        }
    }

    public static function info(string $message, array $context = []): void
    {
        self::log('INFO', $message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::log('ERROR', $message, $context);
    }

    private static function log(string $level, string $message, array $context = []): void
    {
        self::init();

        $entry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'level' => $level,
            'message' => $message,
            'context' => $context,
        ];

        $logFile = self::$logDir . "/" . date('Y-m-d') . ".log";
        $line = json_encode($entry, JSON_UNESCAPED_SLASHES) . PHP_EOL;
        file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);
    }
}
