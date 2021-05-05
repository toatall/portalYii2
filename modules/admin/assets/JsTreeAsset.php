<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\modules\admin\assets;

use yii\web\AssetBundle;

/**
 * JSTree asset bundle.
 *
 * @author toatall
 */
class JsTreeAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'extensions/jsTree/themes/default/style.min.css',
    ];
    public $js = [
        'extensions/jsTree/jstree.min.js',
    ];
    public $depends = [
        'app\modules\admin\assets\AppAsset',
    ];
}
