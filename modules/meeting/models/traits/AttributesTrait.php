<?php
namespace app\modules\meeting\models\traits;

use Yii;

/**
 * Трейт с дополнительными свойствами (полями) 
 * 
 * @property string $time_start
 * @property string $time_end
 * @property string $duration_str
 * @property string $duration_as_duration
 * @property string $title
 * 
 */
trait AttributesTrait
{    
     
    /**
     * Время начала (getter)
     * 
     * @return string
     */
    public function getTime_start()
    {        
        return Yii::$app->formatter->asTime($this->date_start, 'short');
    }
    
    /**
     * Дата начала (setter)
     * 
     * @param string $dateTime
     */
    public function setDate_start_str($dateTime)
    {
        $this->date_start = strtotime($dateTime);        
    }

    /**
     * Дата начала (getter)
     * 
     * @return string|null
     */
    public function getDate_start_str()
    {
        if (empty($this->date_start)) {
            return null;
        }
        return Yii::$app->formatter->asDatetime($this->date_start, 'php:d.m.Y H:i');
    }

    /**
     * Продолжительность (setter)
     * 
     * @param string $duration
     */
    public function setDuration_str($duration)
    {
        $this->duration = \app\helpers\DateHelper::timeToUnix($duration);
    }

    /**
     * Продолжительность (getter)
     * 
     * @return string|null
     */
    public function getDuration_str()
    {
        if (empty($this->duration)) {
            return null;
        }
        return Yii::$app->formatter->asTime(strtotime("1970-01-01") + $this->duration);
    }

    /**
     * Продолжительность в формате duration
     * 
     * @return string
     */
    public function getDuration_as_duration()
    {
        return Yii::$app->formatter->format($this->duration, 'duration');
    }

    /**
     * Время окончания (getter)
     * 
     * @return string|null
     */
    public function getTime_end()
    {
        if (!$this->duration) {
            return null;
        }
        return Yii::$app->formatter->asTime($this->date_start + $this->duration, 'short');
    }

    /**
     * Завершено ли мероприятие
     * 
     * @return bool
     */
    public function isFinished()
    {        
        /** @var \app\modules\meeting\models\Meeting|AttributesTrait $this */      
        $timeEnd = (int)$this->date_start + (int)$this->duration;
        return time() >= $timeEnd;
    }    

    /**
     * Выполняется ли мероприятие в данные момент
     * 
     * @return bool
     */
    public function isUnderway()
    {
        $timeEnd = (int)$this->date_start + (int)$this->duration;
        $now = time();
        return $now > $this->date_start && $now < $timeEnd;
    }

    /**
     * Заголовок мероприятия
     * 
     * @param bool $short сокращенный или полный вывод информации
     * @return string 
     */
    public function getTitle($short = false)
    {
        $place = !empty($this->place) ? '('. $this->place . ') ' : '';
        /** @var \app\modules\meeting\models\Meeting|AttributesTrait $this */   
        return $short ? $place : "{$place}{$this->theme}";
    }    


}