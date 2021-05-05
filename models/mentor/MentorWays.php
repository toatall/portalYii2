<?php

namespace app\models\mentor;

use Yii;

/**
 * This is the model class for table "{{%mentor_ways}}".
 *
 * @property int $id
 * @property string $name
 * @property string $date_create
 * @property string $date_update
 *
 * @property MentorPost[] $mentorPosts
 */
class MentorWays extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%mentor_ways}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['date_create', 'date_update'], 'safe'],
            [['name'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'date_create' => 'Date Create',
            'date_update' => 'Date Update',
        ];
    }

    /**
     * Gets query for [[MentorPosts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMentorPosts()
    {
        return $this->hasMany(MentorPost::class, ['id_mentor_ways' => 'id']);
    }
}
