<?php

namespace app\models\thirty;

use app\models\User;
use Yii;

/**
 * This is the model class for table "{{%thirty_radio_comment}}".
 *
 * @property int $id
 * @property int $id_radio
 * @property string $comment
 * @property string|null $date_create
 * @property string|null $date_update
 * @property string $author
 * @property string|null $date_delete
 *
 * @property ThirtyRadio $radio
 * @property User $modelUser
 */
class ThirtyRadioComment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%thirty_radio_comment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comment'], 'required'],
            [['id_radio'], 'integer'],
            [['comment'], 'string'],
            [['date_create', 'date_update', 'date_delete'], 'safe'],
            [['author'], 'string', 'max' => 250],
            [['id_radio'], 'exist', 'skipOnError' => true, 'targetClass' => ThirtyRadio::className(), 'targetAttribute' => ['id_radio' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'id_radio' => 'ИД радиовыпуска',
            'comment' => 'Комментарий',
            'date_create' => 'Дата создания',
            'date_update' => 'Дата изменения',
            'author' => 'Автор',
            'date_delete' => 'Дата удаления',
        ];
    }

    /**
     * Gets query for [[Radio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRadio()
    {
        return $this->hasOne(ThirtyRadio::className(), ['id' => 'id_radio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelUser()
    {
        return $this->hasOne(User::class, ['username_windows' => 'author']);
    }

    /**
     * {@inheritDoc}
     * @param bool $insert
     * @param array $changedAttributes
     * @throws \yii\db\Exception
     */
    public function afterSave($insert, $changedAttributes)
    {
        $this->updateRadioCountCommnets();
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @throws \yii\db\Exception
     */
    private function updateRadioCountCommnets()
    {
        Yii::$app->db->createCommand("
            update {{%thirty_radio}}
                set count_comments = (select count(id) from {{%thirty_radio_comment}} where id_radio=:id_radio)
            where id=:id
        ")
        ->bindValue(':id', $this->id_radio)
        ->bindValue(':id_radio', $this->id_radio)
        ->execute();
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->author;
    }
}
