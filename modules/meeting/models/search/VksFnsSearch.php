<?php
namespace app\modules\meeting\models\search;

use app\modules\meeting\models\VksFns;

class VksFnsSearch extends MeetingSearch
{   
    /**
     * @inheritDoc
     */
    public static function modelClass()
    {
        return VksFns::class;
    }
}