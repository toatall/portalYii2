<?php

namespace app\modules\comment;

use Yii;

/**
 * `comments` module definition class
 */
class Module extends \yii\base\Module
{
    
    public $defaultRoute = 'comment';
    
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\comment\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {        
        parent::init();        
        Yii::configure($this, ['params' => require __DIR__ . '/config/params.php']);      
    }
}
