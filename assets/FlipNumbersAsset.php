<?php
namespace app\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * 
 */
class FlipNumbersAsset extends AssetBundle
{
    public $sourcePath = '@npm/ekeep-flip-numbers/dist';

    public $js = [
        'flip.min.js',
    ];

    public $css = [
        'flip.min.css',
    ];    

    public $publishOptions = [
        'only' => [
            'flip.min.js',
            'flip.min.css',
        ],
    ];

    public $depends = [
        'app\assets\AppAsset',
    ];    
  
}