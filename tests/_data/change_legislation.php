<?php
namespace tests\_data;

use \Faker;
use Yii;

$facker = Faker\Factory::create();

$result = [];
for($i=0; $i<10; $i++) {
    $result[] = [
        'type_doc' => $facker->randomElement(['tpye_1', 'type_2', 'type_3']),
        'date_doc' => Yii::$app->formatter->asDate($facker->dateTimeBetween('-1 year')),
        'number_doc' => $facker->bothify('??-#####'),
        'name' => $facker->sentence(5),
        'date_doc_1' => Yii::$app->formatter->asDate($facker->dateTime()),
        'date_doc_2' => Yii::$app->formatter->asDate($facker->dateTime()),
        'date_doc_3' => Yii::$app->formatter->asDate($facker->dateTime()),
        'status_doc' => $facker->randomElement(['new', 'edit', 'completed']),
        'text' => $facker->text(),
        'is_anti_crisis' => intval(rand(0, 1)),
        'date_create' => Yii::$app->formatter->asDatetime($facker->date()),
        'date_update' => Yii::$app->formatter->asDateTime($facker->date()),
        'author' => 'admin',
        'log_change' => '-',
    ];
}

return $result;