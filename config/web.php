<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$cookieValidationKey = getenv('SANITEC_COOKIE_VALIDATION_KEY');

if ($cookieValidationKey === false || strlen($cookieValidationKey) < 32) {
    throw new RuntimeException(
        'SANITEC_COOKIE_VALIDATION_KEY must be configured with at least 32 characters.'
    );
}

$config = [
    'id' => 'basic',
    'name' => 'My Application',
    'basePath' => dirname(__DIR__),
    'language' => 'es',
    'sourceLanguage' => 'en-US',
    'timeZone' => 'America/Matamoros',
    'bootstrap' => ['log'],
    'container' => [
        'singletons' => [
            \yii\mail\MailerInterface::class => [
                'class' => \yii\symfonymailer\Mailer::class,
                // send all mails to a file by default.
                'useFileTransport' => true,
                'viewPath' => '@app/mail',
            ],
        ],
    ],
    'controllerMap' => [
        'site' => \app\controllers\SitioController::class,
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => $cookieValidationKey,
        ],
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'user' => [
         'identityClass' => 'app\models\User',
        'enableAutoLogin' => false,   // sin "recordarme" (no tienes auth_key en la BD)
        'enableSession' => true,
        'loginUrl' => ['site/login'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => \yii\mail\MailerInterface::class,
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => \yii\debug\Module::class,
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => \yii\gii\Module::class,
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
