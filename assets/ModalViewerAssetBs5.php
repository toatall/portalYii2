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
class ModalViewerAssetBs5 extends AssetBundle
{
    public $css = [
        
    ];
    public $js = [
        'public/assets/portal/js/urlHelper.js',
        'public/assets/portal/js/modalViewerBs5.js',
    ];
    public $depends = [
        'app\assets\AppAsset',        
    ];
}
