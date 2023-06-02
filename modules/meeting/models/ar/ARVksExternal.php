<?php
namespace app\modules\meeting\models\ar;


use app\modules\meeting\models\VksExternal;

/**
 * 
 * @property int $id
 * @property int $id_meeting
 * @property string $format_holding
 * @property string $person_head
 * @property int $members_count
 * @property int $members_count_ufns
 * @property string $material_translation
 * @property string $link_event
 * @property boolean $is_connect_vks_fns
 * @property string $platform
 * @property string $full_name_support_ufns
 * @property int $date_test_vks
 * @property int $count_notebooks
 * @property int $is_change_time_gymnastic
 * 
 * @property VksExternal $meeting
 * 
 * @author taotall
 */
class ARVksExternal extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%meeting_vks_external}}';
    }

    public function rules()
    {
        return [
            [['id_meeting', 'format_holding', 'members_count', 'material_translation', 
                'members_count_ufns', 'person_head'], 'required'],         
            [['placePost'], 'safe'],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBase()
    {
        return $this->hasOne(VksExternal::class, ['id' => 'id_meeting']);
    }

}