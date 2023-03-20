<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'sqlsrv:server=localhost;database=portal',
    'username' => 'sa',
    'password' => 'P@ssw0rd',
    'charset' => 'utf8',
    'tablePrefix' => 'p_',

    // Schema cache options (for production environment)
    // 'enableSchemaCache' => true,
    // 'schemaCacheDuration' => 60,
    // 'schemaCache' => 'cache',
];
