<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=db;dbname=calinv_db',
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8',

    'schemaMap' => [
        'pgsql'=> [
            'class'=>'yii\db\pgsql\Schema',
            'defaultSchema' => 'public',
        ]
    ],
];
