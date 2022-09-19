<?php

namespace app\modules\rookie;

use Yii;

/**
 * roockie module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $layout = 'index';

    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\rookie\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        // custom initialization code goes here              
        Yii::$app->errorHandler->errorAction = '/rookie/default/error';
        Yii::setAlias('@content/rookie', '@web/public/content/rookie');
        $this->setModules([
            'photohunter' => [
                'class' => 'app\modules\rookie\modules\photohunter\Module',
            ],
            'fortboyard' => [
                'class' => 'app\modules\rookie\modules\fortboyard\Module',
            ],
            'tiktok' => [
                'class' => 'app\modules\rookie\modules\tiktok\Module',
            ],
        ]);        
    }

}
