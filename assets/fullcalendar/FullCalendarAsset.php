<?php

namespace app\assets\fullcalendar;

use yii\web\AssetBundle;

/**
 * 
 */
class FullCalendarAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . DIRECTORY_SEPARATOR . 'assets';
    public $css = [
        'main.min.css',
    ];
    public $js = [        
        'main.min.js',
        'locales-all.min.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];
}
