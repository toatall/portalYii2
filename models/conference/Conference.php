<?php
namespace app\models\conference;


class Conference extends AbstractConference
{
    /**
     * @return int
     */
    public static function getType()
    {
        return self::TYPE_CONFERENCE;
    }

    /**
     * @return string
     */
    public static function getModule()
    {
        return 'conference';
    }
    
    public static function getTypeLabel() 
    {
        return 'Собрания';
    }
}