<?php

namespace app\models;

use app\helpers\Log\LogHelper;
use Yii;

/**
 * This is the model class for table "{{%module}}".
 *
 * @property string $name
 * @property string $description
 * @property int $only_one
 * @property string|null $log_change
 * @property string $date_create
 * @property string $author
 * @property string $class_namespace
 *
 * @property User $modelAuthor
 */
class Module extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%module}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['only_one'], 'integer'],
            [['log_change'], 'string'],
            [['date_create'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['description', 'author'], 'string', 'max' => 250],
            [['class_namespace'], 'string', 'max' => 150],
            [['name'], 'unique'],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author' => 'username_windows']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Наименование',
            'description' => 'Описание',
            'only_one' => 'Только для одного раздела',
            'log_change' => 'Журнал изменений',
            'date_create' => 'Дата создания',
            'author' => 'Автор',
        ];
    }

    /**
     * Gets query for [[ModelAuthor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModelAuthor()
    {
        return $this->hasOne(User::class, ['username_windows' => 'author']);
    }

    /**
     * {@inheritDoc}
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if ($this->isNewRecord) {
            $this->author = Yii::$app->user->identity->username_windows;
        }
        $this->log_change = LogHelper::setLog($this->log_change, ($this->isNewRecord ? 'создание' : 'изменение'));
        return true;
    }
}
