<?php

namespace app\modules\admin;

/**
 * admin module definition class
 */
class Module extends \yii\base\Module
{
    public $layout = 'main';

    public $defaultRoute = 'tree';
    
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        \Yii::$app->errorHandler->errorAction = '/admin/default/error';        
        \Yii::$app->params['bsDependencyEnabled'] = false; 

        $this->setModules([
            'grantaccess' => [
                'class' => 'app\modules\admin\modules\grantaccess\Module',
            ],
        ]);       
    }
}
