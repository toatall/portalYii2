<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * Генерирование данных из данных таблицы {{declare_capmaign_usn}} 
 * для построения графика
 * @author toatall
 */
class DeclareCampaignUsnChart
{

    /**
     * Подготовка данных для рендринга в графике ApexChart
     * @param stirng $orgCode код организации
     * @return array
     */
    public static function generateDataToChart($orgCode)
    {
        $fields = [
            'count_np_provides_reliabe_declare', 
            'count_np_provides_not_required',
        ];
        $model = new DeclareCampaignUsn();
        $dates = self::getUniqueDates($orgCode);
        
        $resultSeries = [];
        $labels = $dates;

        foreach($dates as $date) {                
            $sums = self::getValuesByDate($orgCode, $date, $fields);            
            foreach($fields as $field) {
                $resultSeries[$field][] = $sums[$field];
            }  
        }

        $dataSeries = [];
        foreach($resultSeries as $field => $result) {
            $dataSeries[] = [
                'name' => $model->getAttributeLabel($field),
                'data' => $result,
            ];
        }
        
        return [
            'labels' => array_map(fn($value) => Yii::$app->formatter->asDate($value['date']) ?? $value, $labels),
            'series' => $dataSeries,
        ];
    }

    /**
     * @param string $orgCode
     * @return array
     */
    private static function getUniqueDates($orgCode)
    {
        return (new Query())
            ->from(DeclareCampaignUsn::tableName())
            ->select('date')
            ->groupBy('date')
            ->orderBy(['date' => SORT_ASC])
            ->where(['org_code' => $orgCode])
            ->all();
    }

    /**
     * @param string $orgCode 
     * @param string $date
     * @param array $fields
     * @return array
     */
    private static function getValuesByDate($orgCode, $date, $fields)
    {
        return (new Query())
            ->from(DeclareCampaignUsn::tableName())
            ->select($fields)
            ->where([
                'org_code' => $orgCode,
                'date' => $date,                
            ])
            ->one();
    }

}