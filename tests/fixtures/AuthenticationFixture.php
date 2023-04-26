<?php

namespace app\tests\fixtures;

use app\models\User;
use yii\test\Fixture;

class AuthenticationFixture extends Fixture
{
    /**
     * Создание ролей
     */
    public function load()
    {        
        /** @var \app\models\User $user */
        $user = User::find()->where(['username'=>'admin'])->one();        
        \Yii::$app->user->login($user);
    }
   
} 