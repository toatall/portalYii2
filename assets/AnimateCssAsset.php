<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * include animate.css
 */
class AnimateCssAsset extends AssetBundle
{
    public $sourcePath = '@bower/animate.css';
    
    public $css = [
        'animate.min.css',
    ];
}