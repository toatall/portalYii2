<?php
namespace app\modules\meeting\models;
use yii\db\Query;


/**
 * ВКС внешние 
 * 
 * @property string $format_holding
 * @property string $person_head
 * @property int $members_count
 * @property int $members_count_ufns
 * @property string $material_translation
 * @property string $link_event
 * @property bool $is_connect_vks_fns
 * @property string $platform
 * @property string $full_name_support_ufns
 * @property int $date_test_vks
 * @property int $count_notebooks
 * @property bool $is_change_time_gymnastic
 * 
 * 
 * @author toatall
 */
class VksExternal extends Meeting
{
    use VksExtensionTrait;

    /**
     * {@inheritDoc}
     */
    public static function getType(): string
    {
        return 'vks-external';
    }

    /**
     * {@inheritDoc}
     */
    public static function getTypeLabel(): string
    {
        return 'ВКС внешние';
    }

    /**
     * @inheritDoc
     */
    public static function roleEditor(): string
    {
        return 'meeting-vks-external-editor';
    }

    /**
     * @inheritDoc
     */
    public static function roleViewerAllFields(): string
    {
        return 'meeting-vks-external-viewer';
    }   

}