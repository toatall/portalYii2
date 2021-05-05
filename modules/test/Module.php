<?php

namespace app\modules\test;

/**
 * test module definition class
 */
class Module extends \yii\base\Module
{
    public $defaultRoute = 'test';
    
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\test\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
