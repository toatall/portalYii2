<?php

namespace app\modules\events\assets;

/**
 * Class FancyboxAsset
 * @package app\assets\fancybox
 */
class FancyboxAsset extends \app\assets\fancybox\FancyboxAsset
{    
    public $depends = [
        ContestAsset::class,
    ];    
        
}
