<?php

namespace app\models\calendar;

use app\behaviors\AuthorBehavior;
use app\behaviors\ChangeLogBehavior;
use app\behaviors\DatetimeBehavior;
use app\models\Organization;
use app\models\User;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%calendar}}".
 *
 * @property int $id
 * @property string $date
 * @property string|null $color
 * @property string $code_org
 * @property string $date_create
 * @property string $date_update
 * @property string|null $date_delete
 * @property string $author
 * @property string|null $log_change
 * 
 * @property Organization $organizationModel
 * @property User $userModel
 */
class Calendar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%calendar}}';
    }

    /**
     * Просмотр всех записей
     * @return boolean
     */
    public static function roleModerator()
    {
        return Yii::$app->user->can('admin') || Yii::$app->user->can('calendar-moderator');
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            ['class' => ChangeLogBehavior::class],
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
            [['date'], 'required'],
            [['date', 'date_create', 'date_delete'], 'safe'],
            [['log_change'], 'string'],
            [['color'], 'string', 'max' => 50],
            [['code_org'], 'string', 'max' => 5],
            [['author'], 'string', 'max' => 250],            
            [['code_org'], 'default', 'value'=>Yii::$app->user->identity->default_organization],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'date' => 'Дата',
            'color' => 'Фон',
            'code_org' => 'Организация',
            'date_create' => 'Дата создания',
            'date_delete' => 'Дата удаления',
            'author' => 'Автор',
            'log_change' => 'Журнал изменений',            
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getOrganizationModel()
    {
        return $this->hasOne(Organization::class, ['code' => 'code_org']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUserModel()
    {
        return $this->hasOne(User::class, ['username' => 'author']);
    }

    /**
     * Цвета для текста в календаре
     * @return array
     */
    public function colorsDropdown()
    {
        return [
            'success' => 'зеленый',
            'warning' => 'желтый',
            'danger' => 'красный',            
            'info' => 'бирюзовый',            
            'primary' => 'синий',
            'secondary' => 'серый',
            'dark' => 'темный',            
        ];
    }

    /**
     * Описание цвета
     * @return string
     */
    public function getColorDescription()
    {
        return isset($this->colorsDropdown()[$this->color]) 
            ? $this->colorsDropdown()[$this->color] : $this->color;
    }

    /**
     * @return ActiveQuery
     */
    public function getData()
    {
        $query = $this->hasMany(CalendarData::class, ['id_calendar' => 'id']);
        return $query;
    }
    
    /**
     * {@inheritdoc}
     */
    public function afterFind()
    {
        $this->date = Yii::$app->formatter->asDate($this->date);
        return parent::afterFind();
    }


}
