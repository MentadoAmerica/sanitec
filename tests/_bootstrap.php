<?php

declare(strict_types=1);

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');

require_once __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../vendor/autoload.php';

// Create a SQLite test database for the sample Yii user/login tests.
$testDbFile = __DIR__ . '/../runtime/test.db';
$db = new yii\db\Connection([
    'dsn' => 'sqlite:' . $testDbFile,
    'charset' => 'utf8mb4',
]);
$db->open();

$db->createCommand('CREATE TABLE IF NOT EXISTS user (
    id INTEGER PRIMARY KEY,
    username TEXT NOT NULL UNIQUE,
    auth_key TEXT,
    password_hash TEXT NOT NULL,
    password_reset_token TEXT,
    email TEXT NOT NULL UNIQUE,
    status INTEGER NOT NULL DEFAULT 10,
    created_at INTEGER NOT NULL,
    updated_at INTEGER NOT NULL,
    verification_token TEXT,
    access_token TEXT
)')->execute();

$db->createCommand('CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario INTEGER PRIMARY KEY,
    id_rol INTEGER,
    nombre TEXT,
    apellidos TEXT,
    correo TEXT NOT NULL UNIQUE,
    contrasena TEXT,
    estado INTEGER DEFAULT 1,
    telefono TEXT
)')->execute();

$db->createCommand('DELETE FROM user')->execute();
$db->createCommand('DELETE FROM usuarios')->execute();

$adminExists = (int) $db->createCommand('SELECT COUNT(*) FROM user WHERE username = :username')
    ->bindValue(':username', 'admin')
    ->queryScalar();

if ($adminExists === 0) {
    $passwordHash = Yii::$app?->security ? Yii::$app->security->generatePasswordHash('admin') : password_hash('admin', PASSWORD_DEFAULT);
    $db->createCommand()->insert('user', [
        'id' => 100,
        'username' => 'admin',
        'auth_key' => 'test100key',
        'password_hash' => $passwordHash,
        'email' => 'admin@example.com',
        'status' => 10,
        'created_at' => time(),
        'updated_at' => time(),
        'access_token' => '100-token',
    ])->execute();

    $db->createCommand()->insert('user', [
        'id' => 102,
        'username' => 'demo',
        'auth_key' => 'test102key',
        'password_hash' => password_hash('demo', PASSWORD_DEFAULT),
        'email' => 'demo@example.com',
        'status' => 10,
        'created_at' => time(),
        'updated_at' => time(),
        'access_token' => '102-token',
    ])->execute();

    $db->createCommand()->insert('usuarios', [
        'id_usuario' => 100,
        'id_rol' => 1,
        'nombre' => 'Admin',
        'apellidos' => 'User',
        'correo' => 'admin@example.com',
        'contrasena' => $passwordHash,
        'estado' => 1,
    ])->execute();

    $db->createCommand()->insert('usuarios', [
        'id_usuario' => 101,
        'id_rol' => 2,
        'nombre' => 'Demo',
        'apellidos' => 'User',
        'correo' => 'demo@example.com',
        'contrasena' => password_hash('demo', PASSWORD_DEFAULT),
        'estado' => 1,
    ])->execute();
}

