<?php

namespace app\modules\kadry;

use Yii;

/**
 * kadry module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\kadry\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        \Yii::$app->urlManager->addRules([
            'kadry/<c:\w+|-+>/<id:\d+>' => 'kadry/<c>/view'
            // 'kadry/<controller:\w+>/<id:\d+>' => 'kadry/<controller>/view',
            // 'kadry/<controller:\w+>/<action:\w+>/<id:\d+>' => 'kadry/<controller>/<action>',
        ]);

        $this->setModules([
            'beginner' => [
                'class' => 'app\modules\kadry\modules\beginner\Module',
            ],
        ]);        
    }
}
