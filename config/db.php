<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mongodb://mongodb:27017/db?authSource=admin',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
