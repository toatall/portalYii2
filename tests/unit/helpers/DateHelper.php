<?php
namespace app\tests\unit\helpers;

use Yii;

class DateHelper
{

    public static function asDate($date)
    {
        return Yii::$app->formatter->asDate($date);
    }

}