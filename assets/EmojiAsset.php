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
        'extensions/emoji-picker/lib/css/emoji.css',
        'extensions/emoji-picker/style.css',
    ];
    public $js = [
        'extensions/emoji-picker/lib/js/config.js',
        'extensions/emoji-picker/lib/js/util.js',        
        'extensions/emoji-picker/lib/js/emoji-picker.js',
        'extensions/emoji-picker/lib/js/jquery.emojiarea.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];    
        
}
