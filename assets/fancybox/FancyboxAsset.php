<?php

namespace app\assets\fancybox;

use yii\web\AssetBundle;

/**
 * Class FancyboxAsset
 * @package app\assets\fancybox
 */
class FancyboxAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . DIRECTORY_SEPARATOR . 'assets';
    public $css = [
        'jquery.fancybox.min.css',
    ];
    public $js = [
        'jquery.fancybox.min.js',
        'init.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];    
        
}
