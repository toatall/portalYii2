<?php
namespace app\models\conference;

/**
 * Все события
 * @author toatall
 */
class EventsAll extends AbstractConference
{
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
            ->andWhere(['>=', 'date_start', $start])
            ->andWhere(['<=', 'date_end', $end]);
    }
    
    /**
     * {@inhericdoc}
     */
    public static function getType() {}
    
    /**
     * {@inhericdoc}
     */
    public static function getTypeLabel() {}
    
    /**
     * Заголовок
     * @return string
     */
    public function getTitle()
    {
        if (!$this->accessShowAllFields()) {
            return $this->place;
        }
        return "({$this->place}) {$this->theme}";
    }

}
