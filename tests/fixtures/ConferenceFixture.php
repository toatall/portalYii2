<?php

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class ConferenceFixture extends ActiveFixture
{
    public $modelClass = 'app\models\conference\Conference';

    public $depends = [
        OrganizationFixture::class,
        UserFixture::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->dataFile = codecept_data_dir() . 'conference.php';
    }
} 