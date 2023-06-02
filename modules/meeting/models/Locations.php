<?php

namespace app\modules\meeting\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Query;

/**
 * This is the model class for table "{{%meeting_locations}}".
 *
 * @property int $id
 * @property string $location
 * @property int|null $date_create
 */
class Locations extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%meeting_locations}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['location'], 'required'],
            [['date_create'], 'integer'],
            [['location'], 'string', 'max' => 200],
            [['location'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Идентификатор',
            'location' => 'Кабинет',
            'date_create' => 'Дата создания',
        ];
    }

    /**
     * @inheritDoc
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
     * Список кабинетов (для выбора из списка)
     * 
     * @return array
     */
    public static function listDropDown()
    {
        $query = (new Query())
            ->from(self::tableName())
            ->all();
        return \yii\helpers\ArrayHelper::map($query, 'location', 'location');
    }

}
