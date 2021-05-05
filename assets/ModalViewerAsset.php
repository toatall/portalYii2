<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\assets;
use yii\web\AssetBundle;
/**
 * Description of ModalViewerAsset
 * @author toatall
 */
class ModalViewerAsset extends AssetBundle
{
    public $css = [
        
    ];
    public $js = [
        'js/modalViewer.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
