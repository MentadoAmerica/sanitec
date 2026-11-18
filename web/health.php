<?php

declare(strict_types=1);

// Simple health check without initializing Yii

require __DIR__ . '/../config/environment.php';

echo json_encode([
    'status' => 'ok',
    'environment' => YII_ENV,
    'debug' => YII_DEBUG,
]);
