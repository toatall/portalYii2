<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$dbPortalOld = require __DIR__ . '/dbPortalOld.php';
$ldapParams = require __DIR__ . '/ldap.php';

$config = [
    'id' => 'portal',
    'name' => 'Портал УФНС России по Ханты-Мансийскому автономному округу - Югре',
    'language' => 'ru',
    'layout' => 'portal',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        // админка
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
        // проекты новобранцев
        'rookie' => [
            'class' => 'app\modules\rookie\Module',            
        ],
        // тестирование
        'test' => [
            'class' => 'app\modules\test\Module',            
        ],        
        'gridview' => [
            'class' => '\kartik\grid\Module',
        ],       
        /** @todo надо разобрать */ 
        'events' => [
            'class' => 'app\modules\events\Module',
        ],
        // раздел только для администратора
        'manager' => [
            'class' => 'app\modules\manager\Module',
        ],
        // конкурсы
        'contest' => [
            'class' => 'app\modules\contest\Module',
        ],
    ],    
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'DJAFCKD5hS1paD3QjX1IdZdRp4nlOOfo',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'defaultDuration' => 60, // 1 minute
        ],
        'user' => [
            'class' => 'app\components\UserAuthentication',
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'authManager' => [
            'class' => 'app\components\DbManager',
            //'class' => 'yii\rbac\PhpManager',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            //'useFileTransport' => true,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => '10.186.200.11',
            ],
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
        'dbPortalOld' => $dbPortalOld,
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'locale' => 'ru-RU',
            'defaultTimeZone' => 'Asia/Yekaterinburg',
            'dateFormat' => 'php:d.m.Y',
            'datetimeFormat' => 'php:d.m.Y H:i:s',
            'timeFormat' => 'php:H:i:s',
            'thousandSeparator' => ' ',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            //'suffix' => '.html',
            'rules' => [  
                
                // для конкурса неделя добрых дел
                'dobro' => 'events/dobro',               
                'dobro/<action:\w+>' => 'events/dobro/<action>',     
                
                // просмотр профиля пользователя
                '@<login:\d{4}.+>' => 'user/view-profile',
            ],
        ],
        
        // компонент работы с файлами
        'storage' => [
            'class' => app\components\Storage::class,
        ],
        
        // компонент получения информации о пользователе
        'userInfo' => [
            'class' => app\components\UserInfo::class,
        ],
        
        'ldap' => $ldapParams,
    ],

    // for elfinder
    'controllerMap' => [
        'elfinder' => [
            'class' => 'mihaildev\elfinder\PathController',
            'access' => ['@'],
            'root' => [
                'class' => 'app\components\ElfinderUserPath',
                'basePath' => '@webroot',
                'baseUrl' => '@web',
                'path' => 'files/elfinder/{username}',
                'name' => 'Файлы',
                'options' => [
                    'encoding' => 'Windows-1251'
                ],
            ],
        ],
    ],

    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.83.*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.83.*'],
    ];
}

return $config;
