<?php
namespace app\modules\meeting\models\search;

use app\modules\meeting\models\Conference;

class ConferenceSearch extends MeetingSearch
{    
    /**
     * @inheritDoc
     */
    public static function modelClass()
    {
        return Conference::class;
    }   

}