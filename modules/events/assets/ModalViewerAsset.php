<?php

namespace app\modules\events\assets;
/**
 * Description of ModalViewerAsset
 * @author toatall
 */
class ModalViewerAsset extends \app\assets\ModalViewerAsset
{    
    public $depends = [
        ContestAsset::class,
    ];
}
