<?php

namespace app\modules\dashboardEcr\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/dashboardEcr/assets';

    public $css = [
        'map.css',
    ];    

    public $js = [
        'index.js',
    ];

    public $depends = [
        'app\assets\AppAsset',
    ]; 


    public $publishOptions = [
        'forceCopy' => true,
    ];

}
