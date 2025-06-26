<?php

$common = require __DIR__ . '/web.php';
$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/test_db.php';

$config = array_merge_recursive($common, [
    'id' => 'test-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@tests' => '@app/tests',
    ],
    'components' => [
        'db' => $db,
    ],
]);
return $config;