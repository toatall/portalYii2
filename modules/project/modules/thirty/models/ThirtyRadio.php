<?php

namespace app\modules\project\modules\thirty\models;

use Yii;

/**
 * This is the model class for table "{{%thirty_radio}}".
 *
 * @property int $id
 * @property string $filename
 * @property string|null $description
 * @property string|null $date_create
 * @property string|null $date_update
 * @property string $author
 * @property int|null $count_comments
 * @property int|null $count_view
 * @property int|null $count_like
 *
 * @property ThirtyRadioComment[] $thirtyRadioComments
 * @property ThirtyRadioLike[] $thirtyRadioLikes
 * @property ThirtyRadioVisit[] $thirtyRadioVisits
 */
class ThirtyRadio extends \yii\db\ActiveRecord
{
    protected $basePath = '/files_static/thirty/radio/';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%thirty_radio}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['filename', 'author'], 'required'],
            [['description'], 'string'],
            [['date_create', 'date_update'], 'safe'],
            [['count_comments', 'count_view', 'count_like'], 'integer'],
            [['filename'], 'string', 'max' => 500],
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
            'filename' => 'Имя файла',
            'description' => 'Описание',
            'date_create' => 'Дата создания',
            'date_update' => 'Дата изменения',
            'author' => 'Автор',
            'count_comments' => 'Count Comments',
            'count_view' => 'Count View',
            'count_like' => 'Count Like',
        ];
    }

    /**
     * Gets query for [[ThirtyRadioComments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getThirtyRadioComments()
    {
        return $this->hasMany(ThirtyRadioComment::class, ['id_radio' => 'id']);
    }

    /**
     * @return string
     */
    public function getUrlFileName()
    {
        return $this->basePath . $this->filename;
    }
}
