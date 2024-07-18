<?php

use yii\mongodb\Connection;

return [
    'class' => Connection::class,
    'dsn' => 'mongodb://root:password@mongodb:27017/db?authSource=admin',
    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
