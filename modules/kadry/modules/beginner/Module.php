<?php

namespace app\modules\kadry\modules\beginner;

use Yii;

/**
 * Contest module definition class
 */
class Module extends \yii\base\Module
{
    
    /**
     * {@inheritdoc}
     */
    // public $layout = 'index';
    

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        // \Yii::$app->errorHandler->errorAction = '/beginner/default/error';    
        // custom initialization code goes here
        Yii::configure($this, ['params' => require __DIR__ . '/config/params.php']);
    }

    

}
