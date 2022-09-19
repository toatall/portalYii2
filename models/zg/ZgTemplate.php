<?php

namespace app\models\zg;

use Yii;
use app\models\User;

/**
 * This is the model class for table "{{%zg_template}}".
 *
 * @property int $id
 * @property string $kind
 * @property string|null $description
 * @property string $date_create
 * @property string $date_update
 * @property string $author
 *
 * @property User $author0
 * @property ZgTemplateFile[] $zgTemplateFiles
 */
class ZgTemplate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%zg_template}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kind', 'author'], 'required'],
            [['description'], 'string'],
            [['date_create', 'date_update'], 'safe'],
            [['kind'], 'string', 'max' => 1000],
            [['author'], 'string', 'max' => 250],
            [['author'], 'exist', 'skipOnError' => true, 
                'targetClass' => User::class, 'targetAttribute' => ['author' => 'username_windows']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'kind' => 'Вид обращений',
            'description' => 'Описание',
            'date_create' => 'Дата создания',
            'date_update' => 'Дата изменения',
            'author' => 'Автор',
            'files' => 'Шаблоны',
        ];
    }

    /**
     * Gets query for [[Author0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor0()
    {
        return $this->hasOne(User::class, ['username_windows' => 'author']);
    }

    /**
     * Gets query for [[ZgTemplateFiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getZgTemplateFiles()
    {
        return $this->hasMany(ZgTemplateFile::class, ['id_zg_template' => 'id']);
    }


}
