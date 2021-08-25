<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * 
 */
class EmojiAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'public/vendor/emoji-picker/lib/css/emoji.css',
        'public/vendor/emoji-picker/style.css',        
    ];
    public $js = [
        'public/vendor/emoji-picker/lib/js/config.js',
        'public/vendor/emoji-picker/lib/js/util.js',        
        'public/vendor/emoji-picker/lib/js/emoji-picker.js',
        'public/vendor/emoji-picker/lib/js/jquery.emojiarea.js',        
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];    
        
}
