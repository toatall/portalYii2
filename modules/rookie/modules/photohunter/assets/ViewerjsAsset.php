<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\modules\rookie\modules\photohunter\assets;

use yii\web\AssetBundle;

class ViewerjsAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';    

    public $css = [
        '/public/vendor/viewerjs/viewer.min.css',
    ];
    public $js = [
        '/public/vendor/viewerjs/viewer.min.js',
    ];
    public $depends = [
        'app\assets\AppAsset',        
    ];
}
