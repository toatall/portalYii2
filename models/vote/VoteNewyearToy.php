<?php

namespace app\models\vote;

use Yii;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\db\Query;

/**
 * This is the model class for table "{{%vote_newyear_toy}}".
 *
 * @property int $id
 * @property string $name
 * @property string $code_org
 * @property string|null $department
 * @property string|null $date_create
 * @property string $description
 *
 * @property VoteNewyearToyAnswer[] $voteNewyearToyAnswers
 * @property VoteNewyearToyFile[] $files
 */
class VoteNewyearToy extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%vote_newyear_toy}}';
    }

    /**
     * Кто может голосовать бесконечно
     * @return array
     */
    public static function unlimitVoted()
    {
        return [
            '8600-90-383', // Утбанова
            '8600-90-438',
        ];
    }

    /**
     * Те, кто может смотреть статистику
     * @return array
     */
    public static function userShowStatistic()
    {
        return [
            '8600-90-331',
            '8600-90-438',
            '8600-90-573',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'code_org'], 'required'],
            [['date_create', 'description'], 'safe'],
            [['name'], 'string', 'max' => 1000],
            [['code_org'], 'string', 'max' => 5],
            [['department'], 'string', 'max' => 250],
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
            'description' => 'Description',
            'code_org' => 'Code Org',
            'department' => 'Department',
            'date_create' => 'Date Create',
        ];
    }

    /**
     * Gets query for [[VoteNewyearToyAnswers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVoteNewyearToyAnswers()
    {
        return $this->hasMany(VoteNewyearToyAnswer::class, ['id_vote_newyear_toy' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(VoteNewyearToyFile::class, ['id_vote_newyear_toy' => 'id']);
    }

    /**
     * Голосовал ли текущий пользователь за текущую игрушку
     * @return bool
     */
    public function votedIt()
    {
        $username = Yii::$app->user->identity->username;
        return (new Query())
            ->from('{{%vote_newyear_toy_answer}}')
            ->where([
                'username' => $username,
                'id_vote_newyear_toy' => $this->id,
            ])
            ->exists();
    }

    /**
     * @return ActiveDataProvider
     */
    public function voteResults()
    {
        return new ActiveDataProvider([
            'query' => VoteNewyearToyAnswer::find()
                ->select('t.*, view_user.fio, view_user.ldap_department department')
                ->alias('t')
                ->leftJoin('{{%view_user}} view_user', 'view_user.username = t.username')
                ->where(['t.id_vote_newyear_toy' => $this->id]),
        ]);
    }

    /**
     * @return int|string
     */
    public function countVote()
    {
        return (new Query())
            ->from('{{%vote_newyear_toy_answer}}')
            ->where(['id_vote_newyear_toy' => $this->id])
            ->count();
    }


    /**
     * Если голосовал текущий пользователь (вообще)
     * @return bool
     */
    public static function isVoted()
    {
        $username = Yii::$app->user->identity->username;
        if (in_array($username, static::unlimitVoted())) {
            return false;
        }
        return (new Query())
            ->from('{{%vote_newyear_toy_answer}}')
            ->where(['username' => $username])
            ->exists();
    }

    /**
     * @return bool
     */
    public static function isUnlimited()
    {
        return in_array(Yii::$app->user->identity->username, static::unlimitVoted());
    }

    /**
     * @return bool
     */
    public static function showBtnVote()
    {
        return in_array(date('d.m.Y'), [
            '21.12.2020',
            '22.12.2020',
            '23.12.2020',
        ]);
    }

    /**
     * @return bool
     */
    public static function showBtnStatistic()
    {
        return in_array(Yii::$app->user->identity->username, static::userShowStatistic());
    }



}
