<?php

namespace app\modules\rookie\modules\photohunter;

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
    public $controllerNamespace = 'app\modules\rookie\modules\photohunter\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        // custom initialization code goes here
        Yii::$app->params['bsVersion'] = '4.x';                        
        Yii::$app->errorHandler->errorAction = '/rookie/default/error';
        Yii::setAlias('@content/rookie/photohunter', '@web/public/content/rookie/photohunter');       
    }

}
