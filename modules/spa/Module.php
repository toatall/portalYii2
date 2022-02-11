<?php

namespace app\modules\spa;

use Yii;

/**
 * spa module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @var string
     */
    public $defaultRoute = 'spa';
    
    /**
     * @var string
     */
    //public $layout = 'index';  

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();      
    }
}
