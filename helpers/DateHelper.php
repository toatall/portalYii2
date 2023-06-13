<?php
namespace app\helpers;

use DateTimeImmutable;
use Yii;

class DateHelper
{


    /**
     * Преобразование текстовой даты в формат unix
     * 
     * @param string $date
     * @return int
     */
    public static function dateTimeToUnix(string $date): int
    {
        return strtotime($date);
    }

    /**
     * Преобразование времени (формата hh:mm) в формат unix
     * 
     * @param string $time
     * @return int
     */
    public static function timeToUnix(string $time): int
    {
        $t = strtotime($time);
        $h = date('H', $t) * 60 * 60;
        $m = date('i', $t) * 60;
        return $h + $m;
    }





    /**
     * Сравнение 2х дат в соотрвествии с установленным форматом
     * @param $date1 string
     * @param $date2 string
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public static function equalsDates($date1, $date2)
    {
        if ($date1 == null || $date2 == null) {
            return false;
        }
        $formatter = Yii::$app->formatter;
        return $formatter->asDate($date1) == $formatter->asDate($date2);
    }

    /**
     * Добавление к дате $date текущего времени
     * @param $date string
     * @return string|null
     * @throws \yii\base\InvalidConfigException
     */
    public static function asDateWithTime($date, $time = null)
    {
        if ($date == null) {
            return  null;
        }
        if ($time == null) {
            $time = [
                'h' => date('H'),
                'm' => date('i'),
                's' => date('s'),
            ];
        }
        $dt = new \DateTime($date);
        $dt->setTime($time['h'], $time['m'], $time['s']);
        return Yii::$app->formatter->asDatetime($dt);
    }

    /**
     * Текущая дата
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function today()
    {
        return Yii::$app->formatter->asDate('now');
    }

    /**
     * Максимальная дата
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function maxDate()
    {
        $intMax = PHP_INT_SIZE == 4 ? PHP_INT_MAX : PHP_INT_MAX >> 32;
        return Yii::$app->formatter->asDate($intMax);
    }

    /**
     * @param string $date1
     * @param string|null $date2
     * @return int
     * @throws \yii\base\InvalidConfigException
     */
    public static function dateDiffDays($date1, $date2=null)
    {
        $date1 = Yii::$app->formatter->asDateTime($date1);
        if ($date2 == null) {
            $date2 = Yii::$app->formatter->asDatetime('now');
        }
        $diff = self::dateDiff($date1, $date2);
        $result = $diff->days;
        if ($diff->invert) {
            $result = $result * -1;
        }
        return $result;
    }

    /**
     * @param $date1
     * @param $date2
     * @return \DateInterval|false
     */
    public static function dateDiff($date1, $date2)
    {
        $d1 = date_create($date1);
        $d2 = date_create($date2);
        return date_diff($d1, $d2, true);        
    }
    
    /**
     * Имя месяца по его номеру
     * @param integer $month
     * @return string
     */
    public static function getMonthName($month)
    {
        $months = [
            1 => 'Январь',
            2 => 'Февраль',
            3 => 'Март',
            4 => 'Апрель',
            5 => 'Май',
            6 => 'Июнь',
            7 => 'Июль',
            8 => 'Август',
            9 => 'Сентябрь',
            10 => 'Октябрь',
            11 => 'Ноябрь',
            12 => 'Декабрь',
        ];
        return $months[$month] ?? null;
    }
    
    /**
     * Проверка текущей (или указанной) даты в указанных диапазонах дат
     * @param string $date1
     * @param string $date2
     * @param DateTimeImmutable $dateToday
     * @return mixed
     */
    public static function isDateTodayBetween($date1, $date2, $dateToday = 'today')
    {
        $d1 = new \DateTimeImmutable($date1);
        $d2 = new \DateTimeImmutable($date2);
        $dateToday = new \DateTimeImmutable($dateToday);
        return ($dateToday >= $d1) && ($dateToday <= $d2);        
    }

    /**
     * Преобразование даты к формату Y-m-d H:i:s для сохранения в MS SQL
     *
     * @param string|\DateTime|\DateTimeImmutable $date
     * @return string
     */
    public static function dateSqlFormat($date = 'now')
    {
        if ($date instanceof \DateTimeImmutable || $date instanceof \DateTime) {
            $dt = $date;
        }
        else {
            $dt = new \DateTimeImmutable($date);
        }
        return $dt->format('Ymd H:i:s');
    }
    
}