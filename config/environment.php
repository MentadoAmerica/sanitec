<?php

declare(strict_types=1);

// Load .env.local file if it exists (development helper only)
$envFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env.local';
if (file_exists($envFile)) {
    $lines = @file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    if (is_array($lines)) {
        foreach ($lines as $line) {
            // Skip comments
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            
            // Parse KEY=VALUE
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                
                // Remove quotes if present
                if (preg_match('/^["\'](.+)["\']$/', $value, $matches)) {
                    $value = $matches[1];
                }
                
                // Set environment variable (only if not already set by Apache/system)
                if (getenv($key) === false) {
                    putenv("$key=$value");
                }
            }
        }
    }
}

$environment = getenv('SANITEC_ENV');
if ($environment === false || $environment === '') {
    $environment = 'prod';
}

if (!in_array($environment, ['dev', 'prod'], true)) {
    throw new RuntimeException('SANITEC_ENV must be either "dev" or "prod".');
}

if (defined('YII_ENV') && YII_ENV !== $environment) {
    throw new RuntimeException('SANITEC_ENV conflicts with the existing YII_ENV value.');
}

$debugEnabled = $environment === 'dev' && getenv('SANITEC_DEBUG') === '1';
if (defined('YII_DEBUG') && YII_DEBUG !== $debugEnabled) {
    throw new RuntimeException('SANITEC_DEBUG conflicts with the existing YII_DEBUG value.');
}

defined('YII_ENV') or define('YII_ENV', $environment);
defined('YII_DEBUG') or define('YII_DEBUG', $debugEnabled);
