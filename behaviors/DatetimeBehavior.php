<?php
namespace app\behaviors;

use yii\behaviors\TimestampBehavior;

/**
 * DatetimeBehavior
 * @author toatall
 */
class DatetimeBehavior extends TimestampBehavior
{
    /**
     * {@inheritdoc}
     */
    protected function getValue($event)
    {        
        if ($this->value === null) {
            return \Yii::$app->formatter->asDatetime(time());
        }       
        
        return parent::getValue($event);
    }
    
}
