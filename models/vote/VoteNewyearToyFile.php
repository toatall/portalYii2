<?php

namespace app\models\vote;

use Yii;

/**
 * This is the model class for table "{{%vote_newyear_toy_file}}".
 *
 * @property int $id
 * @property int|null $id_vote_newyear_toy
 * @property string $file_name
 * @property string|null $date_create
 *
 * @property VoteNewyearToy $voteNewyearToy
 */
class VoteNewyearToyFile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%vote_newyear_toy_file}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_vote_newyear_toy'], 'integer'],
            [['file_name'], 'required'],
            [['date_create'], 'safe'],
            [['file_name'], 'string', 'max' => 500],
            [['id_vote_newyear_toy'], 'exist', 'skipOnError' => true, 'targetClass' => VoteNewyearToy::className(), 'targetAttribute' => ['id_vote_newyear_toy' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_vote_newyear_toy' => 'Id Vote Newyear Toy',
            'file_name' => 'File Name',
            'date_create' => 'Date Create',
        ];
    }

    /**
     * Gets query for [[VoteNewyearToy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVoteNewyearToy()
    {
        return $this->hasOne(VoteNewyearToy::className(), ['id' => 'id_vote_newyear_toy']);
    }
}
