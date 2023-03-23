<?php
namespace app\modules\admin\assets;

use yii\web\AssetBundle;

/**
 * JSTree asset bundle.
 *
 * @author toatall
 */
class JsTreeAsset extends AssetBundle
{
    
    public $sourcePath = '@npm/jstree/dist';
    
    public $js = [
        'jstree.min.js',
    ];
    public $css = [
        'themes/default/style.min.css',
    ];    

    public $depends = [
        'app\modules\admin\assets\AppAsset',
    ];    
    
    
    
}
