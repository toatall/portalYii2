<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * 
 */
class SpoilerAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'extensions/spoiler_jq_ind/spoiler.css',
    ];
    public $js = [
        'extensions/spoiler_jq_ind/spoiler.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];    
        
}
