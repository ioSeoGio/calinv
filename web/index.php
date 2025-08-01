<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

if (file_exists(dirname(__DIR__) . '/.env.local')) {
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__), '.env.local');
    $dotenv->load();
}
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
