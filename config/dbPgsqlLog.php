<?php


return [
    'class' => 'yii\db\Connection',    
    'dsn' => 'pgsql:host=host;dbname=portal_log',   
    'username' => '',
    'password' => '',
    'charset' => 'utf8',    

    // Schema cache options (for production environment)
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 60,
    'schemaCache' => 'cache',
];
