<?php

/**
 * This class only exists here for IDE (PHPStorm/Netbeans/...) autocompletion.
 * This file is never included anywhere.
 * Adjust this file to match classes configured in your application config, to enable IDE autocompletion for custom components.
 * Example: A property phpdoc can be added in `__Application` class as `@property \vendor\package\Rollbar|__Rollbar $rollbar` and adding a class in this file
 * ```php
 * // @property of \vendor\package\Rollbar goes here
 * class __Rollbar {
 * }
 * ```
 */
class Yii {
    /**
     * @var \yii\web\Application|\yii\console\Application|__Application
     */
    public static $app;
}

/**
 * @property \yii\rbac\DbManager $authManager 
 * @property \app\components\UserAuthentication|__WebUser $user
 * @property \yii\db\Connection $dbDks
 * @property \yii\db\Connection $dbPortalOld
 * @property \yii\db\Connection $dbPgsqlLog
 * @property \app\components\Storage $storage
 */
class __Application {
}

/**
 * @property \yii\web\User|\app\models\User $identity
 */
class __WebUser {
}
