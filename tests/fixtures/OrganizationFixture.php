<?php

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class OrganizationFixture extends ActiveFixture
{
    public $modelClass = 'app\models\Organization';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->dataFile = codecept_data_dir() . 'organization_data.php';
    }    
} 