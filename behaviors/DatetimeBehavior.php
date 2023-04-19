<?php
namespace app\behaviors;

use app\helpers\DateHelper;
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
            return DateHelper::dateSqlFormat();
        }       
        
        return parent::getValue($event);
    }
    
}
