<?php
namespace app\models\conference;


class VksUfns extends AbstractConference
{
    /**
     * @return int
     */
    public static function getType()
    {
        return self::TYPE_VKS_UFNS;
    }

    /**
     * @return string
     */
    public static function getModule()
    {
        return 'vks-ufns';
    }

    public static function getTypeLabel() 
    {
        return 'ВКС с УФНС';
    }
    
    /**
     * {@inheritdoc}
     * @return boolean
     */
    public static function isView() 
    {
        return true;
    }

}