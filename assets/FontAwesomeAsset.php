<?php
namespace app\assets;

use yii\web\AssetBundle;

/**
 * Asset font-awesome
 */
class FontAwesomeAsset extends AssetBundle
{
    public $sourcePath = '@bower/font-awesome';

    public $js = [
        'js/all.js',
    ];

    public $css = [
        'css/all.css',
    ];    

    public $publishOptions = [
        'only' => [
            'js/all.js',
            'css/all.css',
            'webfonts/*',
        ],
    ];

    public $depends = [
        'app\assets\AppAsset',
    ];    
}