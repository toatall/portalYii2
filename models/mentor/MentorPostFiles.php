<?php

namespace app\models\mentor;

use Yii;

/**
 * This is the model class for table "{{%mentor_post_files}}".
 *
 * @property int $id
 * @property int $id_mentor_post
 * @property string $filename
 * @property string $date_create
 *
 * @property MentorPost $mentorPost
 */
class MentorPostFiles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%mentor_post_files}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_mentor_post', 'filename'], 'required'],
            [['id_mentor_post'], 'integer'],
            [['date_create'], 'safe'],
            [['filename'], 'string', 'max' => 250],
            [['id_mentor_post'], 'exist', 'skipOnError' => true, 'targetClass' => MentorPost::className(), 'targetAttribute' => ['id_mentor_post' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_mentor_post' => 'Id Mentor Post',
            'filename' => 'Filename',
            'date_create' => 'Date Create',
        ];
    }

    /**
     * Gets query for [[MentorPost]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMentorPost()
    {
        return $this->hasOne(MentorPost::className(), ['id' => 'id_mentor_post']);
    }

    /**
     * {@inheritDoc}
     */
    public function afterDelete()
    {
        \Yii::$app->storage->deleteFile($this->file_name);
        parent::afterDelete();
    }
}
