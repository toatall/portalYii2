<?php

namespace app\modules\events;

/**
 * events module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\events\controllers';
    
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->params = require __DIR__ . '/config/params.php';
        // custom initialization code goes here
    }
}
