<?php

namespace app\modules\contest\models;

use app\models\User;
use Yii;
use yii\db\Query;

/**
 * This is the model class for table "{{%contest_vote_data}}".
 *
 * @property int $id
 * @property int $id_contest_main
 * @property string $nomination
 * @property string $title
 * @property string $file
 * @property string $file_type
 * @property string $date_create
 * @property string $date_update
 * @property string $author
 * 
 * @property VoteMain $voteMain
 * @property User $authorModel
 */
class VoteData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%contest_vote_data}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_contest_main', 'nomination', 'file', 'file_type'], 'required'],
            [['id_contest_main'], 'integer'],
            [['date_create', 'date_update', 'description'], 'safe'],
            [['nomination'], 'string', 'max' => 200],
            [['file'], 'string', 'max' => 500],
            [['title'], 'string', 'max' => 1000],
            [['file_type'], 'string', 'max' => 50],
            [['author'], 'string', 'max' => 250],
            [['id_contest_main'], 'exist', 'skipOnError' => true, 'targetClass' => VoteMain::class, 'targetAttribute' => ['id_contest_main' => 'id']],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author' => 'username']],
        ];
    }


    /**
     * Gets query for [[VoteMain]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVoteMain()
    {
        return $this->hasOne(VoteMain::class, ['id' => 'id_contest_main']);
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
     * Is the current user voted by this model
     * @return boolean
     */
    public function isVoted()
    {
        return (new Query())
            ->from('{{%contest_vote_answer}}')
            ->where([
                'username' => Yii::$app->user->identity->username,
                'id_contest_vote_data' => $this->id,
            ])
            ->exists();
    }

    /**
     * Is the current user voted by this nomination
     * @return boolean
     */
    public function isVotedNomination()
    {
        return (new Query())
            ->from('{{%contest_vote_answer}} t')
            ->rightJoin('{{%contest_vote_data}} d', 't.id_contest_vote_data = d.id')
            ->where([
                'username' => Yii::$app->user->identity->username,
                'd.nomination' => $this->nomination,
            ])
            ->exists();
    }

    /**
     * Count answers
     * @return int
     */
    public function getCountAnswers()
    {
        return (new Query())
            ->from('{{%contest_vote_answer}}')
            ->where(['id_contest_vote_data' => $this->id])
            ->count();
    }

}
