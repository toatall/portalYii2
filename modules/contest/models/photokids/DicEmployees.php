<?php

namespace app\modules\contest\models\photokids;

use yii\db\Query;
use yii\helpers\ArrayHelper;

class DicEmployees 
{
    /**
     * @return array
     */
    public static function getList()
    {
        $query = (new Query())
            ->from('{{%contest_photo_kids_dic_employees}}')
            ->orderBy(['fio' => SORT_ASC])
            ->all();
        return ArrayHelper::map($query, 'fio', 'fio');
    }
}