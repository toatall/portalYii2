<?php
namespace app\models\conference;


class VksFns extends AbstractConference
{
    /**
     * @return int
     */
    public static function getType()
    {
        return self::TYPE_VKS_FNS;
    }

    /**
     * @return string
     */
    public static function getModule()
    {
        return 'vks-fns';
    }
    
    public static function getTypeLabel() 
    {
        return 'ВКС с ФНС';
    }
}