<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\modules\events\assets;

use app\assets\AppAsset;
use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ContestAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/libs';
     
    public $css = [
        // 'vendor/bootstrap/css/bootstrap.min.css',
        'css/styles.css',
        '/css/all.min.css',
    ];
    
    public $js = [
        // 'vendor/jquery/jquery.min.js',
        // 'vendor/bootstrap/js/bootstrap.bundle.min.js',    
        'js/jquery.easing.min.js',
        'js/scripts.js',
    ];
    
    public $depends = [
        // 'yii\bootstrap5\BootstrapAsset',
        // 'yii\web\YiiAsset',
        // 'yii\bootstrap5\BootstrapAsset',
        // 'yii\bootstrap5\BootstrapPluginAsset',
        AppAsset::class,
    ];
    
    public function init() 
    {
                
        // \Yii::$app->assetManager->bundles['yii\web\JqueryAsset'] = [
        //     'js' => [],
        // ];        
        // \Yii::$app->assetManager->bundles['yii\bootstrap\BootstrapAsset'] = [
        //     'js' => [],
        // ];
        // \Yii::$app->assetManager->bundles['yii\bootstrap\BootstrapPluginAsset'] = [
        //     'js' => [],
        // ];
        
        parent::init();
    }
}
