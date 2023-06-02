<?php
namespace app\modules\meeting\models\search;

use app\modules\meeting\models\VksUfns;

class VksUfnsSearch extends MeetingSearch
{

    /**
     * @inheritDoc
     */
    public static function modelClass()
    {
        return VksUfns::class;
    }    

}