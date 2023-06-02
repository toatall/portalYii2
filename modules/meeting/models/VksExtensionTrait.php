<?php
namespace app\modules\meeting\models;

use Yii;

/**
 * @property VKsExternal $extension
 */
trait VksExtensionTrait
{

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExtension()
    {        
        return $this->hasOne(VksExternalExtension::class, ['id_meeting' => 'id']);
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels() 
    {
        return \yii\helpers\ArrayHelper::merge(parent::attributeLabels(), [
            'responsible' => 'Организатор мероприятия  (кто проводит мероприятие)',
        ]);
    }    

}