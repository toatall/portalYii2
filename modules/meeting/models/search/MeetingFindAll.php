<?php
namespace app\modules\meeting\models\search;

use app\modules\meeting\helpers\MeetingHelper;
use Yii;

class MeetingFindAll 
{

    /**
     * Поиск мероприятий за период с $start по $end с типами мероприятий $types
     * 
     * @param string $start дата начала
     * @param string $end дата окончания
     * @param string $types типы мероприятий (разделитель - `,`), если ничего не указано, то используются все типы мероприятий
     * @return MeetingSearch[]|null
     */
    public function findAllMeeting($start, $end, $types) 
    {
        $dateStart = strtotime(Yii::$app->formatter->asDate($start));
        $dateEnd = strtotime(Yii::$app->formatter->asDate($end));        
        $results = [];
        if (empty($types)) {
            $arrayTypes = MeetingHelper::allTypes();
        }
        else {
            $arrayTypes = explode(',', $types);
        }

        $mapTypesToClassNames = MeetingHelper::mapTypesToClassNames();

        foreach($arrayTypes as $type) {
            if (isset($mapTypesToClassNames[$type])) {               
                /** @var MeetingSearch $modelSearch */
                $modelSearch = new $mapTypesToClassNames[$type]['classSearch']();
                $resultQuery = $modelSearch->findPublic($dateStart, $dateEnd);
                if ($resultQuery !== null) {
                    $results = array_merge($results, $resultQuery);
                }
            }
        }

        return $results;
    } 
    
    /**
     * Мероприятия на текущий день
     * 
     * @uses \app\models\menu\MenuBuilder::buildLeftAddMenuContent()
     * @return array
     */
    public static function findToday()
    {
        $now = Yii::$app->formatter->asDate('now');
        $dateStart = strtotime($now . ' 00:00:00');
        $dateEnd = strtotime($now . ' 23:59:59');

        $queries = [];
        $types = MeetingHelper::mapTypesToClassNames();
        foreach($types as $typeName => $type) {
            /** @var MeetingSearch $model */
            $model = new $type['classSearch']();
            $queries[$typeName] = [
                'data' => $model->findPublic($dateStart, $dateEnd),
                'label' => $type['classBase']::getTypeLabel(),
                'isViewerAllFields' => $type['classBase']::isViewerAllFields(),
            ];
        }
        return $queries;
    }

}