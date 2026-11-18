<?php

$isDevelopment = defined('YII_ENV') && YII_ENV === 'dev';

$database = [
    'host' => getenv('SANITEC_DB_HOST'),
    'name' => getenv('SANITEC_DB_NAME'),
    'username' => getenv('SANITEC_DB_USER'),
    'password' => getenv('SANITEC_DB_PASSWORD'),
];

if ($isDevelopment) {
    $localDefaults = [
        'host' => '127.0.0.1',
        'name' => 'sanitec',
        'username' => 'root',
    ];

    foreach ($localDefaults as $key => $value) {
        if ($database[$key] === false || $database[$key] === '') {
            $database[$key] = $value;
        }
    }

    if ($database['password'] === false) {
        $database['password'] = '';
    }
}

$missingConfiguration = [];
$environmentNames = [
    'host' => 'SANITEC_DB_HOST',
    'name' => 'SANITEC_DB_NAME',
    'username' => 'SANITEC_DB_USER',
];
foreach (['host', 'name', 'username'] as $key) {
    if ($database[$key] === false || $database[$key] === '') {
        $missingConfiguration[] = $environmentNames[$key];
    }
}

if ($database['password'] === false || (!$isDevelopment && $database['password'] === '')) {
    $missingConfiguration[] = 'SANITEC_DB_PASSWORD (non-empty in production)';
}

if ($missingConfiguration !== []) {
    throw new RuntimeException(
        'Missing or insecure database configuration: ' . implode(', ', $missingConfiguration)
    );
}

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=' . $database['host'] . ';dbname=' . $database['name'] . ';charset=utf8mb4',
    'username' => $database['username'],
    'password' => $database['password'],
    'charset' => 'utf8mb4',
];