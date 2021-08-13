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
        
        Yii::$app->params['bsVersion'] = '4.x';
        // custom initialization code goes here                
        Yii::$app->errorHandler->errorAction = '/rookie/default/error';
        Yii::setAlias('@content/rookie', '@web/public/content/rookie');
    }
}
