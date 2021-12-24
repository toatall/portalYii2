<?php

namespace app\modules\calendar\models;

use app\behaviors\AuthorBehavior;
use app\behaviors\ChangeLogBehavior;
use app\behaviors\DatetimeBehavior;
use app\models\Organization;
use app\models\User;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%calendar}}".
 *
 * @property int $id
 * @property string $date
 * @property string|null $color
 * @property string $org_code
 * @property string $type_text
 * @property string $description
 * @property string $is_global
 * @property string $sort
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


    const DEFAULT_COLOR = 'secondary';

    /**
     * Список дат
     * @var string
     */
    public $dates;

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
            [['type_text', 'description'], 'required'],
            [['dates'], 'required', 'on' => 'create-multi'],
            [['dates'], function($attribute) {                
                $dates = explode('/', trim($this->$attribute));
                foreach ($dates as $date) {
                    if (date('d.m.Y', strtotime($date)) != $date) {
                        $this->addError('dates', "Дата `{$date}` введена некорректно!");
                    }
                }                
            }],
            [['date', 'date_create', 'date_delete'], 'safe'],
            [['log_change', 'description'], 'string'],
            [['color', 'type_text'], 'string', 'max' => 50],
            [['org_code'], 'string', 'max' => 5],
            [['author'], 'string', 'max' => 250],
            [['sort'], 'integer'],  
            [['is_global'], 'boolean'],
            [['is_global', 'sort'], 'default', 'value'=>0],          
            [['org_code'], 'default', 'value'=>Yii::$app->user->identity->default_organization],
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
            'dates' => 'Даты',
            'color' => 'Цвет фона мероприятия',
            'type_text' => 'Тип мероприятия',
            'org_code' => 'Организация',
            'description' => 'Описание',
            'is_global' => 'Глобальная запись (для всех организаций)',
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
        return $this->hasOne(Organization::class, ['code' => 'org_code']);
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
    public static function colorsDropdown()
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
     * 
     * @return string
     */
    public static function colorsDisplay()
    {
        $result = [];
        foreach (self::colorsDropdown() as $color => $title) {
            $result[$color] = '<span class="badge badge-' . $color . ' fa-1x">' . $title . '</span>';
        }
        return $result;
    }

    /**
     * Типы мероприятий
     * @return array
     */
    public function dropDownTypeText()
    {
        $query = (new Query())
            ->from('{{%calendar_types}}')
            ->all();
        return ArrayHelper::map($query, 'type_text', 'type_text');
    }

    /**
     * Описание цвета
     * @return string
     */
    public function getColorDescription()
    {
        return isset(self::colorsDropdown()[$this->color]) 
            ? self::colorsDropdown()[$this->color] : $this->color;
    }

    /**
     * Получение текущей модели
     * Используется для значения в ArrayHelper::map с группировкой по типу события
     * @return Calendar
     */
    public function getFull()
    {
        return $this;
    }

    /**
     * @return ActiveQuery
     */
    // public function getData()
    // {
    //     $query = $this->hasMany(CalendarData::class, ['id_calendar' => 'id']);
    //     return $query;
    // }

    /**
     * Вывод списка событий с обрезкой текста (для грида)
     * @return array
     */
    // public function getDataByText(string $separator = '<br />')
    // {
    //     $result = [];
    //     foreach ($this->getData()->all() as $item) {
    //         $result[] = '<span class="text-' . $item['color'] . '">' 
    //             . StringHelper::truncateWords($item['description'], 5) 
    //             . ($item['is_global'] ? ' <span class="badge badge-success">глобальная</span>' : '')
    //             . '</span>';
    //     }        
    //     return $result;
    // }

    /**
     * Вывод списка событий разбитых на группы
     * @return array
     */
    // public function getDataWithGroup()
    // {
    //     $result = [];
    //     foreach ($this->getData()->all() as $item) {
    //         $result[$item->type_text][] = $item;
    //     }
    //     return $result;
    // }
    
    /**
     * {@inheritdoc}
     */
    public function afterFind()
    {
        $this->date = Yii::$app->formatter->asDate($this->date);
        return parent::afterFind();
    }


}
