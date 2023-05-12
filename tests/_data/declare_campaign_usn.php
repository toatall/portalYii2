<?php
namespace tests\_data;

use Yii;
use yii\db\Query;

$orgsData = array_keys((new Query())
    ->from('{{%organization}}')
    ->indexBy('code')
    ->all());

$dates = [
    Yii::$app->formatter->asDate('2023-05-01'),
    Yii::$app->formatter->asDate('2023-05-02'),
    Yii::$app->formatter->asDate('2023-05-03'),
    Yii::$app->formatter->asDate('2023-05-04'),
];

$result = [];


foreach($dates as $date) {
    foreach($orgsData as $org) {
        $randValues = [
            'ul' => rand(200, 450),
            'ip' => rand(300, 500),
            'reliabe_declare' => rand(50, 150),
            'not_required' => rand(100, 250),
        ];

        $result[] = [
            'year' =>  date('Y'),
            'date' => $date,
            'org_code' => $org,
            'count_np' => $randValues['ul'] + $randValues['ip'],
            'count_np_ul' => $randValues['ul'],
            'count_np_ip' => $randValues['ip'],
            'count_np_provides_reliabe_declare' => $randValues['reliabe_declare'],
            'count_np_provides_not_required' => $randValues['not_required'],
        ];
    }
}

return $result;