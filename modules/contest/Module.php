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
        $this->setModules([            
            'space' => [
                'class' => 'app\modules\contest\modules\space\Module',
            ],
            'pets' => [
                'class' => 'app\modules\contest\modules\pets\Module',
            ],              
        ]);
    }
}
