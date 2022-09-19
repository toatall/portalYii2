<?php

namespace app\modules\rookie\modules\tiktok;

use Yii;

/**
 * Tiktok module definition class
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
    public $controllerNamespace = 'app\modules\rookie\modules\tiktok\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        // custom initialization code goes here       
        Yii::$app->errorHandler->errorAction = '/rookie/default/error';
        Yii::setAlias('@content/rookie/tiktok', '@web/public/content/rookie/tiktok');
    }

}
