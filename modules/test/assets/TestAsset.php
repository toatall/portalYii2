<?php

namespace app\modules\test\assets;

use yii\web\AssetBundle;

/**
 * Загрузка контейнеров для тестирования
 */
class TestAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/test.js?v=20210809',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];    
        
}
