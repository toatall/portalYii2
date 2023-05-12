<?php
$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db/test_db.php';

// $webConfig = require __DIR__ . '/web.php';
// return \yii\helpers\ArrayHelper::merge($webConfig, [
//     'id' => 'basic-tests',
//     'components' => [
//         'db' => $db,
//         'request' => [
//             'cookieValidationKey' => 'test',
//             'enableCsrfValidation' => false,
//             // but if you absolutely need it set cookie domain to localhost
//             /*
//             'csrfCookie' => [
//                 'domain' => 'localhost',
//             ],
//             */
//         ],
//     ],
// ]);

/**
 * Application configuration shared by all test types
 */

return [
    'id' => 'basic-tests',
    'basePath' => dirname(__DIR__),
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'language' => 'ru-RU',
    'components' => [
        'db' => $db,
        'mailer' => [
            'useFileTransport' => true,
        ],
        'assetManager' => [
            'basePath' => __DIR__ . '/../web/assets',
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
        'user' => [
            'class' => 'app\components\UserAuthentication',
            'identityClass' => 'app\models\User',
        ],
        'request' => [
            'cookieValidationKey' => 'test',
            'enableCsrfValidation' => false,
            // but if you absolutely need it set cookie domain to localhost
            /*
            'csrfCookie' => [
                'domain' => 'localhost',
            ],
            */
        ],
        'authManager' => [
            'class' => 'app\components\DbManager',          
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
            //'defaultDuration' => 60, // 1 minute
        ],       
        'storage' => [
            'class' => app\components\Storage::class,
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'locale' => 'ru-RU',
            'defaultTimeZone' => 'Asia/Yekaterinburg',
            'dateFormat' => 'php:d.m.Y',
            'datetimeFormat' => 'php:d.m.Y H:i:s',
            'timeFormat' => 'php:H:i:s',
            'thousandSeparator' => ' ',
        ],
    ],
    'params' => $params,
];
