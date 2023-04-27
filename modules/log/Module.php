<?php

namespace app\modules\log;

/**
 * log module definition class
 */
class Module extends \yii\base\Module
{

    /**
     * {@inheritdoc}
     */
    public $layout = 'main';

    /**
     * {@inheritdoc}
     */
    public $defaultRoute = 'log';   

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();        
    }
}
