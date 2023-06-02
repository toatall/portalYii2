<?php
namespace app\modules\meeting\models;

/**
 * Собрание
 * 
 * 
 * @author toatall
 */
class Conference extends Meeting
{
    /**
     * {@inheritDoc}
     */
    public static function getType(): string
    {
        return 'conference';
    }

    /**
     * {@inheritDoc}
     */
    public static function getTypeLabel(): string
    {
        return 'Собрания';
    }

    /**
     * @inheritDoc
     */
    public static function roleEditor(): string
    {
        return 'meeting-conference-editor';
    }

    /**
     * @inheritDoc
     */
    public static function roleViewerAllFields(): string
    {
        return 'meeting-conference-viewer';
    }      

}