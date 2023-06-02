<?php
namespace app\modules\meeting\models;

/**
 * ВКС с ФНС 
 * 
 * 
 * @author toatall
 */
class VksFns extends Meeting
{
    /**
     * @inheritDoc
     */
    public static function getType(): string
    {
        return 'vks-fns';
    }

    /**
     * @inheritDoc
     */
    public static function getTypeLabel(): string
    {
        return 'ВКС с ФНС';
    }

    /**
     * @inheritDoc
     */
    public static function roleEditor(): string
    {
        return 'meeting-vks-fns-editor';
    }

    /**
     * @inheritDoc
     */
    public static function roleViewerAllFields(): string
    {
        return 'meeting-vks-fns-viewer';
    }   
    
}