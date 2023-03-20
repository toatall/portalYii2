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
        'public/assets/portal/css/site.css',
        'public/assets/portal/css/css-loader.css',
        'public/assets/portal/css/menu.css',
         
        // icon-addons (depends - font-awesome)
        'public/assets/portal/css/icon-addons.css',                
    ];
    public $js = [
        'public/assets/portal/js/voteHelper.js', // ?
        'public/assets/portal/js/main.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',       
        'yii\bootstrap5\BootstrapAsset',
        'yii\bootstrap5\BootstrapPluginAsset',
    ];
}
