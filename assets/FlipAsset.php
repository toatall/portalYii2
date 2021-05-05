<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * 
 */
class FlipAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'extensions/flip/flip.min.css',
    ];
    public $js = [
        'extensions/flip/flip.min.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];    
        
}
