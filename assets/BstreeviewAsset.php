<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * 
 */
class BstreeviewAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'public/vendor/bstreeview/css/bstreeview.min.css',      
    ];
    public $js = [
        'public/vendor/bstreeview/js/bstreeview.min.js',        
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];    
        
}
