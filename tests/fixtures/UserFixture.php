<?php

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class UserFixture extends ActiveFixture
{
    public $modelClass = 'app\models\User';

    public $depends = [
        RoleFixture::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->dataFile = codecept_data_dir() . 'user_data.php';
    }
} 