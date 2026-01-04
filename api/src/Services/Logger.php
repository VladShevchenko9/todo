<?php

namespace App\Services;

final class Logger
{
    private const LOG_DIR = __DIR__ . '/../../logs';

    /**
     * @param string $message
     * @param array $context
     */
    public static function info(string $message, array $context = []): void
    {
        self::log('INFO', $message, $context);
    }

    /**
     * @return string
     */
    private static function getLogFile(): string
    {
        $fileName = date('Y-m-d') . '.log';

        return self::LOG_DIR . '/' . $fileName;
    }

    /**
     * @param string $level
     * @param string $message
     * @param array $context
     */
    private static function log(string $level, string $message, array $context = []): void
    {
        $file = self::getLogFile();

        $date = date('Y-m-d H:i:s');

        if (sizeof($context)) {
            $message .= ' ' . json_encode($context, JSON_UNESCAPED_UNICODE);
        }

        $line = "[{$date}] {$level}: {$message}\n";

        @file_put_contents($file, $line, FILE_APPEND | LOCK_EX);
    }
}
