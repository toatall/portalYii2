<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * 
 */
class OwlCarouselAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'extensions/OwlCarousel2/dist/assets/owl.carousel.min.css',
    ];
    public $js = [
        'extensions/OwlCarousel2/dist/owl.carousel.min.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];    
        
}
