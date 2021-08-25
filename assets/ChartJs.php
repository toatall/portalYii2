<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * 
 */
class ChartJs extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'public/vendor/chart.js/Chart.min.css',
    ];
    public $js = [
        'public/vendor/chart.js/Chart.min.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];    
        
}
