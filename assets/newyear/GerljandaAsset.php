<?php

namespace app\assets\newyear;

use yii\web\AssetBundle;

/**
 * 
 */
class GerljandaAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'gerljanda';
    public $css = [
        'gerljanda.css',
        //'snow.css',
    ];
    public $js = [
        'script.js',
        //'snow.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];
}
