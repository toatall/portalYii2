<?php

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class ChangeLegislationFixture extends ActiveFixture
{
    public $modelClass = 'app\models\ChangeLegislation';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->dataFile = codecept_data_dir() . 'change_legislation.php';
    }
} 