<?php

namespace app\models\calendar;

use app\behaviors\AuthorBehavior;
use app\behaviors\DatetimeBehavior;
use app\models\User;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%calendar_data}}".
 *
 * @property int $id
 * @property int $id_calendar
 * @property string|null $color
 * @property string $description
 * @property boolean $is_global
 * @property string $type_text
 * @property int $sort
 * @property string $date_create
 * @property string $author
 *
 * @property Calendar $calendar
 * @property User $userModel
 */
class CalendarData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%calendar_data}}';
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
            [['id_calendar', 'description', 'type_text'], 'required'],
            [['id_calendar', 'sort'], 'integer'],
            [['description'], 'string'],
            [['date_create'], 'safe'],
            [['color', 'type_text'], 'string', 'max' => 50],
            [['author'], 'string', 'max' => 250],
            [['is_global'], 'boolean'],
            [['is_global', 'sort'], 'default', 'value'=>0],
            [['id_calendar'], 'exist', 'skipOnError' => true, 'targetClass' => Calendar::class, 'targetAttribute' => ['id_calendar' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'id_calendar' => 'ИД календаря',
            'color' => 'Цвет фона описания',
            'description' => 'Описание',
            'date_create' => 'Дата создания',
            'author' => 'Автор',
            'is_global' => 'Глобальная запись (для всех организаций)',
            'type_text' => 'Тип мероприятия',
        ];
    }

    /**
     * Gets query for [[Calendar]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCalendar()
    {
        return $this->hasOne(Calendar::class, ['id' => 'id_calendar']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserModel()
    {
        return $this->hasOne(User::class, ['username' => 'author']);
    }

    /**
     * @return array
     */
    public function dropDownTypeText()
    {
        $query = (new Query())
            ->from('{{%calendar_types}}')
            ->all();
        return ArrayHelper::map($query, 'type_text', 'type_text');
    }
}
