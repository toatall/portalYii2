<?php

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class DeclareCampaignUsnFixture extends ActiveFixture
{
    public $modelClass = 'app\models\DeclareCampaignUsn';

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
        $this->dataFile = codecept_data_dir() . 'declare_campaign_usn.php';
    }
} 