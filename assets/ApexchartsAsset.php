<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Apexcharts
 */
class ApexchartsAsset extends AssetBundle
{   

    public $sourcePath = '@npm2/apexcharts/dist';

    public $js = [
        'apexcharts.js',
    ];
    public $css = [
        'apexcharts.css',
    ];    

    public $depends = [
        'app\assets\AppAsset',
    ];    
        
}
