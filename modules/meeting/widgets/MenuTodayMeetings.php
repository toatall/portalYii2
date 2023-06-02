<?php
namespace app\modules\meeting\widgets;

use app\modules\meeting\models\search\MeetingFindAll;
use yii\bootstrap5\Widget;

class MenuTodayMeetings extends Widget
{
    private $_dataMeetings;

    public function init()
    {
        parent::init();
        $this->_dataMeetings = MeetingFindAll::findToday();
    }

    public function run()
    {
        return $this->render('today', [
            'queryResult' => $this->_dataMeetings,
        ]);        
    }

}