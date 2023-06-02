<?php
namespace app\modules\meeting\models\search;

use app\modules\meeting\models\VksKonturTalk;

class VksKonturTalkSearch extends MeetingSearch
{
    /**
     * @inheritDoc
     */
    public static function modelClass()
    {
        return VksKonturTalk::class;
    }
}