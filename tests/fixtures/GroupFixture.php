<?php

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class GroupFixture extends ActiveFixture
{
    public $modelClass = 'app\models\Group';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->dataFile = codecept_data_dir() . 'group_data.php';
    }
} 