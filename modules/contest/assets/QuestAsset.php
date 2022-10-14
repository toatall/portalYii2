<?php
namespace app\modules\contest\assets;

use yii\web\AssetBundle;

class QuestAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [        
        'public/assets/contest/quest/css/main.css',
        'public/vendor/fontawesome/css/all.min.css',
    ];
    public $js = [
        'public/assets/contest/quest/js/main.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapPluginAsset',
    ];
}
