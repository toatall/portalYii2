<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\modules\admin\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'public/assets/portal/css/css-loader.css',
    ];
    public $js = [];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
        'yii\bootstrap5\BootstrapPluginAsset',
    ];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $view = Yii::$app->view;
        $view->registerJs(<<<JS
           
            // показывать окно загрузки при отправке pjax
            $(document).on('pjax:send', function(event) {
                $('#div-loader').addClass('is-active');
            });
            // скрыть окно загрузки при завершении pjax
            $(document).on('pjax:complete', function() {
                $('#div-loader').removeClass('is-active');
            });
            
        JS); 
    }
}
