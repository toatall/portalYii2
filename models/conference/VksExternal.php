<?php
namespace app\models\conference;

use yii\helpers\ArrayHelper;

/**
 * 
 */
class VksExternal extends AbstractConference
{
    /**
     * @return int
     */
    public static function getType()
    {
        return self::TYPE_VKS_EXTERNAL;
    }
    
    public static function getTypeLabel() 
    {
        return 'ВКС внешние';
    }

    /**
     * @return string
     */
    public static function getModule()
    {
        return 'vks-external';
    }
    
    /**
     * {@inheritdoc}
     * @return array
     */
    public function rules() 
    {
        return ArrayHelper::merge(parent::rules(), [
            [['responsible', 'format_holding', 'members_count', 'material_translation', 'members_count_ufns', 'person_head', 'duration'], 'required'],         
            [['placePost'], 'safe'],
        ]);
    }        
    
    
    public function attributeLabels() 
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'responsible' => 'Организатор мероприятия  (кто проводит мероприятие)',
        ]);
    }
    
     
}