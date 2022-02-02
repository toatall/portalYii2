<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'public/assets/portal/css/site.css?ver=20211206',
        'public/assets/portal/css/css-loader.css',
        'public/assets/portal/css/menu.css',
         
         // font-awesome
        'public/vendor/fontawesome/css/all.min.css',

        // bootstrap-4-advanced-toast
        'public/vendor/bootstrap-4-advanced-toast/css/bs4Toast.css',
    ];
    public $js = [
        // font-awesome
        //'public/vendor/fontawesome/js/all.min.js',

        // bootstrap-4-advanced-toast
        'public/vendor/bootstrap-4-advanced-toast/js/bs4-toast.js',


        'js/voteHelper.js',
        'js/main.js',        
        
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
    ];
}
