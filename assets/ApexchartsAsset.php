<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Apexcharts
 */
class ApexchartsAsset extends AssetBundle
{   

    public $sourcePath = '@npm/apexcharts/dist';

    public $js = [
        'apexcharts.min.js',
    ];
    public $css = [
        'apexcharts.css',
    ];    

    public $depends = [
        'app\assets\AppAsset',
    ];    
        
}
