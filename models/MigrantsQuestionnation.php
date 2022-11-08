<?php

namespace app\models;

use app\behaviors\AuthorBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%migrants_questionnation}}".
 *
 * @property int $id
 * @property string $ul_name
 * @property string $ul_inn
 * @property string|null $ul_kpp
 * @property string $date_send_notice
 * @property string $region_migrate
 * @property string $cause_migrate
 * @property int $date_create
 * @property int $date_update
 * @property string|null $author
 */
class MigrantsQuestionnation extends \yii\db\ActiveRecord
{

    /**
     * Наименование роли модератора
     * @return string|null
     */
    public static function roleModerator()
    {
        return Yii::$app->params['migrants-questionnation']['roles']['moderator'] ?? null;
    }

    /**
     * Проверка прав на редактирование
     * @return bool
     */
    public static function isModerator()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        $roleModerator = self::roleModerator();
        if (Yii::$app->user->can('admin') || (Yii::$app->user->can($roleModerator))) {
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%migrants_questionnation}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ul_name', 'ul_inn', 'date_send_notice', 'region_migrate', 'cause_migrate'], 'required'],
            [['date_send_notice'], 'safe'],
            [['cause_migrate'], 'string'],
            [['date_create', 'date_update'], 'integer'],
            [['ul_name'], 'string', 'max' => 500],
            [['ul_inn'], 'string', 'max' => 12],
            [['ul_kpp'], 'string', 'max' => 10],
            [['region_migrate'], 'string', 'max' => 200],
            [['author'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'ul_name' => 'Наименование ЮЛ',
            'ul_inn' => 'ИНН',
            'ul_kpp' => 'КПП',
            'date_send_notice' => 'Дата направления уведомления',
            'region_migrate' => 'Регион РФ',
            'cause_migrate' => 'Причина миграции',
            'date_create' => 'Дата создания',
            'date_update' => 'Дата изменения',
            'author' => 'Автор',
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
                'updatedAtAttribute' => 'date_update',
            ],
            ['class' => AuthorBehavior::class],     
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function afterFind()
    {
        if ($this->date_send_notice) {
            $this->date_send_notice = Yii::$app->formatter->asDate($this->date_send_notice);
        }
    }
    
}
