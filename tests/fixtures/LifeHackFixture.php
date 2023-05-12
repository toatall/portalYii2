<?php

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class LifeHackFixture extends ActiveFixture
{
    public $modelClass = 'app\models\lifehack\Lifehack';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->dataFile = codecept_data_dir() . 'lifehack_data.php';
    }
} 