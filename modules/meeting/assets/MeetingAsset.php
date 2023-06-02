<?php
namespace app\modules\meeting\assets;

class MeetingAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@app/modules/meeting/assets';

    public $css = [
        'css/meeting.css',
        'css/dayPost.css'
    ];
    public $js = [
        //        
    ];
    public $depends = [
        //
    ];

    public $publishOptions = [
        'forceCopy' => YII_ENV_DEV == 'dev',
    ];
}