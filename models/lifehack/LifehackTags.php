<?php

namespace app\models\lifehack;

use app\behaviors\DatetimeBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%lifehack_tags}}".
 *
 * @property string $tag
 * @property string $date_create
 */
class LifehackTags extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%lifehack_tags}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tag'], 'required'],
            [['date_create'], 'safe'],
            [['tag'], 'string', 'max' => 100],
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [            
            [
                'class' => DatetimeBehavior::class,
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
            'tag' => 'Тэг',
            'date_create' => 'Дата создания',
        ];
    }

    /**
     * @return array
     */
    public static function getDropDownList(): array
    {
        return ArrayHelper::map(self::find()->all(), 'tag', 'tag');
    }
}
