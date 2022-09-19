<?php

namespace app\modules\test;

use Yii;

/**
 * test module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @var string
     */
    public $defaultRoute = 'test';
    
    /**
     * @var string
     */
    public $layout = 'index';
    
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
        // // Yii::$app->params['bsVersion'] = '4.x';
        // // custom initialization code goes here        
        // Yii::$app->assetManager->bundles['yii\web\JqueryAsset'] = ['js' => []];
        // Yii::$app->assetManager->bundles['yii\bootstrap\BootstrapPluginAsset'] = ['js' => []];
        // Yii::$app->assetManager->bundles['yii\bootstrap\BootstrapAsset'] = ['css' => [], 'js' => []];
        // Yii::$app->assetManager->bundles['yii\bootstrap4\BootstrapPluginAsset'] = ['js' => []];
        Yii::$app->errorHandler->errorAction = '/test/test/error';
        /*
        Yii::$app->setComponents([
            'errorHandler' => [
                'class' => 'yii\web\ErrorHandler',
                'action' => '/test/test/error',
            ],
        ]);*/
    }
}
