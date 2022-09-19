<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * ChartJs
 */
class ChartJsAsset extends AssetBundle
{   

    public $sourcePath = '@npm/chart.js/dist';

    public $js = [
        'chart.min.js',
    ];
    public $css = [
        'Chart.min.css',
    ];    

    public $depends = [
        'app\assets\AppAsset',
    ];    
        
}
