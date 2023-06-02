<?php
namespace app\modules\meeting\models;

/**
 * ВКС с УФНС
 * 
 * @author toatall
 */
class VksUfns extends Meeting
{
    /**
     * {@inheritDoc}
     */
    public static function getType(): string
    {
        return 'vks-ufns';
    }

    /**
     * {@inheritDoc}
     */
    public static function getTypeLabel(): string
    {
        return 'ВКС с УФНС';
    }

    /**
     * @inheritDoc
     */
    public static function roleEditor(): string
    {
        return 'meeting-vks-ufns-editor';
    }
    
    /**
     * @inheritDoc
     */
    public static function roleViewerAllFields(): string|bool
    {
        return true;
    }   
  

}