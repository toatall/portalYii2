<?php

namespace app\models;

use app\behaviors\AuthorBehavior;
use app\behaviors\ChangeLogBehavior;
use app\behaviors\DatetimeBehavior;
use Yii;

/**
 * This is the model class for table "{{%change_legislation}}".
 *
 * @property int $id
 * @property string $type_doc
 * @property string|null $date_doc
 * @property string|null $number_doc
 * @property string $name
 * @property string|null $date_doc_1
 * @property string|null $date_doc_2
 * @property string|null $date_doc_3
 * @property string|null $status_doc
 * @property string|null $text
 * @property string $date_create
 * @property string $date_update
 * @property string $author
 * @property string|null $log_change
 *
 * @property User $authorModel
 */
class ChangeLegislation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%change_legislation}}';
    }

    /**
     * @return string
     */
    public static function roleModerator() 
    {
        return Yii::$app->params['change-legislation']['roles']['moderator'];
    }

    /**
     * @return bool
     */
    public static function isRoleModerator()
    {
        if (Yii::$app->user->can('admin') || Yii::$app->user->can(self::roleModerator())) {
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type_doc', 'name'], 'required'],
            [['date_doc', 'date_doc_1', 'date_doc_2', 'date_doc_3', 'date_create', 'date_update'], 'safe'],
            [['name', 'text', 'log_change'], 'string'],
            [['type_doc', 'number_doc', 'status_doc', 'author'], 'string', 'max' => 250],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author' => 'username']],
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
                'updatedAtAttribute' => 'date_update',
            ],
            ['class' => AuthorBehavior::class],
            ['class' => ChangeLogBehavior::class],         
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Ид',
            'type_doc' => 'Вид документа',
            'date_doc' => 'Дата документа',
            'number_doc' => 'Номер документа',
            'name' => 'Наименование',
            'date_doc_1' => 'Дата вступления в силу',
            'date_doc_2' => 'Дата внесения в ГД',
            'date_doc_3' => 'Дата рассмотрения ГД',
            'status_doc' => 'Статус',
            'text' => 'Текст',
            'date_create' => 'Дата создания',
            'date_update' => 'Дата изменения',
            'author' => 'Автор',
            'log_change' => 'Журнал изменений',
        ];
    }

    /**
     * Gets query for [[AuthorModel]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthorModel()
    {
        return $this->hasOne(User::class, ['username' => 'author']);
    }

    /**
     * {@inheritdoc}
     */
    public function afterFind()
    {
        if ($this->date_doc !== null) {
            $this->date_doc = Yii::$app->formatter->asDate($this->date_doc);
        }
        if ($this->date_doc_1 !== null) {
            $this->date_doc_1 = Yii::$app->formatter->asDate($this->date_doc_1);
        }
        if ($this->date_doc_2 !== null) {
            $this->date_doc_2 = Yii::$app->formatter->asDate($this->date_doc_2);
        }
        if ($this->date_doc_3 !== null) {
            $this->date_doc_3 = Yii::$app->formatter->asDate($this->date_doc_3);
        }
    }

}
