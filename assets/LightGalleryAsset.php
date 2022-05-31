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
class LightGalleryAsset extends AssetBundle
{
    public $basePath = '@webroot/public/vendor/lightGallery';
    public $baseUrl = '@web/public/vendor/lightGallery';
    public $css = [
        'css/lightgallery.css',
    ];
    public $js = [
        'lightgallery.min.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];
}
