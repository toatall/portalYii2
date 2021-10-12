<?php

namespace app\modules\contest;

/**
 * Contest module definition class
 */
class Module extends \yii\base\Module
{
    public $layout = 'main';
    
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\contest\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        \Yii::$app->errorHandler->errorAction = '/contest/default/error';    
        // custom initialization code goes here
    }
}
