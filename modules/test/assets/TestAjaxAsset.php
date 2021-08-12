<?php

namespace app\modules\test\assets;

use yii\web\AssetBundle;

/**
 * Загрузка контейнеров для тестирования через ajax
 * Для этого необходимо указать div-контейнер с аттрибутами
 * -- data-id - идентификатор теста
 */
class TestAjaxAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/test/test-ajax.js?v=20210809',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];        
}
