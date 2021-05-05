<?php
namespace common\behaviors;

use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

/**
 * Преобразование даты после получения из БД
 * @author toatall
 */
class DatetimeReadFormatBehavior extends AttributeBehavior
{
    /**
     * Аттрибуты для преобразования даты и веремни
     * @var string[]
     */
    public $attributesDate = [];
    
    /**
     * Аттрибуты для преобразования даты
     * @var string[] 
     */
    public $attributesDatetime = [];
    
    /**
     * {@inheritdoc}
     */
    public function events() 
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'convert',
        ];                
    }
    
    /**
     * Преобразование
     */
    public function convert()
    {
        $this->convertDate();
        $this->convertDatetime();
    }
    
    /**
     * Преобразование в формат `дата`
     */
    private function convertDate()
    {
        foreach ($this->attributesDate as $attributeDate)
        {
            if ($this->owner->hasProperty($attributeDate) && $this->owner->{$attributeDate})
            {                
                $this->owner->{$attributeDate} = \Yii::$app->formatter->asDate($this->owner->{$attributeDate});
            }
        }
    }
    
    /**
     * Преобразование в формат `дата и время`
     */
    private function convertDatetime()
    {
        foreach ($this->attributesDatetime as $attributeDatetime)
        {
            if ($this->owner->hasProperty($attributeDatetime) && $this->owner->{$attributeDatetime})
            {
                $this->owner->{$attributeDatetime} = \Yii::$app->formatter->asDatetime($this->owner->{$attributeDatetime});
            }
        }
    }
    
}
