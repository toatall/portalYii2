<?php
namespace app\modules\meeting\models;

/**
 * ВКС внешние 
 * 
 * 
 * @author toatall
 */
class VksKonturTalk extends Meeting
{
    /**
     * {@inheritDoc}
     */
    public static function getType(): string
    {
        return 'vks-kontur-talk';
    }

    /**
     * {@inheritDoc}
     */
    public static function getTypeLabel(): string
    {
        return 'ВКС по Контур.Толк';
    }

    /**
     * @inheritDoc
     */
    public static function roleEditor(): string
    {
        return 'meeting-vks-kontur-talk-editor';
    }

    /**
     * Роль редактора для ИФНС
     * 
     * @return string
     */
    public static function roleEditorIfns()
    {
        return 'meeting-vks-kontur-talk-editor-ifns'; 
    }

    /**
     * @inheritDoc
     */
    public static function roleViewerAllFields(): string|bool
    {
        return true;
    }   
    
}