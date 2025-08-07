<?php

use app\bootstrap\SetUp;
use app\filters\AdminFilter;
use yii\symfonymailer\Mailer;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'language' => 'ru-RU',
    'id' => 'calinv',
    'name' => 'CalInv',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'log-reader',
        SetUp::class,
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@views' => '@app/views',
    ],
    'modules' => [
        'log-reader' => [
            'class' => \kriss\logReader\Module::class,
            'as login_filter' => AdminFilter::class,
            'aliases' => [
                'Main' => '@app/runtime/logs/app.log',
            ],
            //'defaultTailLine' => 200,
        ],
    ],
    'components' => [
        'authManager' => [
            'class' => \yii\rbac\DbManager::class,
        ],
        'session' => [
            'class' => \yii\web\CacheSession::class,
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'H9faj5kCQM',
            'enableCookieValidation' => false,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'src\Entity\User\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'class' => \lib\ErrorHandler::class,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'portfolio' => '/personal-share/index',
                'issuer' => '/issuer/index',
				'login' => 'auth/login',
                'site/login' => 'auth/login',
                '/faq' => '/site/faq',
                '' => '/issuer/index',
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['*'],
    ];
}

return $config;
