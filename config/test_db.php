<?php

return [
    'class' => \yii\db\Connection::class,
    'dsn' => 'sqlite:' . dirname(__DIR__) . '/runtime/test.db',
    'charset' => 'utf8mb4',
];
