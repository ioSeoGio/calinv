<?php

use yii\mongodb\Connection;

return [
    'class' => Connection::class,
    'dsn' => env('MONGODB_DSN'),
    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
