<?php

namespace app\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * Include Fancyapps
 */
class FancyappsUIAsset extends AssetBundle
{
    public static $includeAutoplay = false;

    public $sourcePath = '@npm/fancyapps--ui/dist';
    
    public $css = [
        'fancybox.css',
    ];

    public $js = [
        'fancybox.umd.js',        
    ];

    public function init()
    {
        parent::init();
        Yii::$app->getView()->registerCss(<<<CSS
            .fancybox__container {
                z-index: 1000000 !important;
            }
        CSS);
        
        if (self::$includeAutoplay) {
            $this->js[] = 'carousel.autoplay.umd.js';
        }
    }
}