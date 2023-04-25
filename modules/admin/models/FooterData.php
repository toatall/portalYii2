<?php

namespace app\modules\admin\models;

use app\models\Footer;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%footer_data}}".
 *
 * @property int $id
 * @property int $id_type
 * @property string $url
 * @property string $text
 * @property string $target
 * @property string|null $options
 * @property int|null $date_create
 *
 * @property FooterType $type
 */
class FooterData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%footer_data}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_type', 'url', 'text'], 'required'],
            [['id_type', 'date_create'], 'integer'],
            [['target', 'options'], 'string'],
            [['url', 'text'], 'string', 'max' => 500],
            [['id_type'], 'exist', 'skipOnError' => true, 'targetClass' => FooterType::class, 'targetAttribute' => ['id_type' => 'id']],
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
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'id_type' => 'Раздел',
            'url' => 'Ссылка',
            'text' => 'Наименование',
            'target' => 'Аттрибут target',
            'options' => 'Опции',
            'date_create' => 'Дата создания',
        ];
    }

    /**
     * Gets query for [[Type]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(FooterType::class, ['id' => 'id_type']);
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
