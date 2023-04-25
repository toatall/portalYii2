<?php

namespace app\modules\admin\models;

use app\models\Footer;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%footer_type}}".
 *
 * @property int $id
 * @property string $name
 * @property int|null $date_create
 *
 * @property FooterData[] $footerDatas
 */
class FooterType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%footer_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['date_create'], 'integer'],
            [['name'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'name' => 'Наименование',
            'date_create' => 'Дата создания',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [            
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'date_create',
                'updatedAtAttribute' => null,
            ],
        ];
    }    

    /**
     * Gets query for [[FooterDatas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFooterDatas()
    {
        return $this->hasMany(FooterData::class, ['id_type' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function afterDelete()
    {
        parent::afterDelete();
        Footer::clearCache();
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        Footer::clearCache();
    }

}
