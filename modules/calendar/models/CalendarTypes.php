<?php

namespace app\modules\calendar\models;

use app\behaviors\AuthorBehavior;
use app\behaviors\DatetimeBehavior;

/**
 * This is the model class for table "{{%calendar_types}}".
 *
 * @property int $id
 * @property string $type_text
 * @property string $date_create
 * @property string $date_update
 * @property string $author
 *
 */
class CalendarTypes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%calendar_types}}';
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
                'updatedAtAttribute' => 'date_update',
            ],
            ['class' => AuthorBehavior::class],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type_text'], 'required'],
            [['date_create', 'date_update'], 'safe'],
            [['type_text'], 'string', 'max' => 50],
            [['author'], 'string', 'max' => 250],
            [['type_text'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'type_text' => 'Наименование',
            'date_create' => 'Дата создания',
            'date_update' => 'Дата изменения',
            'author' => 'Автор',
        ];
    }

}
