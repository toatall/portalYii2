<?php
namespace app\models\conference;

/**
 * Все события
 * @author toatall
 */
class EventsAll extends AbstractConference
{

    public static function getModule() {}

    /**
     * Поиск для календаря
     * @param string $start
     * @param string $end
     * @return \yii\db\ActiveQuery
     * @uses \app\controllers\ConferenceController::actionCalendarData($start, $end)
     */
    public static function findEvents($start, $end)
    {
        return parent::findPublic()
            ->andWhere('date_start between cast(:d1 as datetime) and cast(:d2 as datetime)', [
                ':d1' => $start,
                ':d2' => $end,
            ]);
    }
    
    /**
     * {@inhericdoc}
     */
    public static function getType() {}
    
    /**
     * {@inhericdoc}
     */
    public static function getTypeLabel() {}
    
    

}
