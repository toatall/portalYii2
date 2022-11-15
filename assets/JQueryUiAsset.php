<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Apexcharts
 */
class JQueryUiAsset extends AssetBundle
{   

    public $sourcePath = '@bower/jquery-ui';

    public $js = [
        'jquery-ui.js',
    ];
    
    public $css = [
        'themes/base/all.css',
    ];    

    public $depends = [
        'app\assets\AppAsset',
    ];    
        
}
