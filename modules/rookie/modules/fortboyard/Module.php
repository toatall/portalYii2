<?php

namespace app\modules\rookie\modules\fortboyard;

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
    public $controllerNamespace = 'app\modules\rookie\modules\fortboyard\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        // custom initialization code goes here                            
        Yii::$app->errorHandler->errorAction = '/rookie/default/error';
        Yii::setAlias('@content/rookie/fortboyard', '@web/public/content/rookie/fortboyard');
    }

}
