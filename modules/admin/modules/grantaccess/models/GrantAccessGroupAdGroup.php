<?php

namespace app\modules\admin\modules\grantaccess\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%grant_access_group__adgroup}}".
 *
 * @property int $id
 * @property int $id_group
 * @property string $group_name
 * @property int $date_end
 * @property int|null $date_create
 * @property int|null $date_update
 *
 * @property GrantAccessGroup $group
 */
class GrantAccessGroupAdGroup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%grant_access_group__adgroup}}';
    }

    /**
     * {@inheritDoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'date_create',
                'updatedAtAttribute' => 'date_update',
            ],           
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_group', 'group_name'], 'required'],
            [['id_group', 'date_create', 'date_update', 'date_end'], 'integer'],
            [['group_name'], 'string', 'max' => 100],
            [['id_group'], 'exist', 'skipOnError' => true, 'targetClass' => GrantAccessGroup::class, 'targetAttribute' => ['id_group' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [        
            'id' => 'ИД',
            'group_name' => 'Наименование группы',
            'date_end' => 'Дата окончания',
            'date_create' => 'Дата создания',            
        ];
    }

    /**
     * Gets query for [[Group]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(GrantAccessGroup::class, ['id' => 'id_group']);
    }


    /**
     * {@inheritDoc}
     */
    public function afterSave($insert, $changedAttributes)
    {
        Yii::$app->grantAccess->clearAdGroupCache($this->group->unique);
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * {@inheritDoc}
     */
    public function beforeDelete()
    {        
        Yii::$app->grantAccess->clearAdGroupCache($this->group->unique);
        return parent::beforeDelete();
    }

}
