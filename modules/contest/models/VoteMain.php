<?php

namespace app\modules\contest\models;

use Yii;

/**
 * This is the model class for table "{{%contest_vote_main}}".
 *
 * @property int $id
 * @property string $tag
 * @property string $title
 * @property string $groups_vote
 * @property string $date_start
 * @property string $date_end
 *
 * @property VoteData[] $voteData
 */
class VoteMain extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%contest_vote_main}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tag', 'groups_vote', 'date_start', 'date_end', 'title'], 'required'],
            [['date_start', 'date_end'], 'safe'],
            [['tag'], 'string', 'max' => 50],
            [['groups_vote'], 'string', 'max' => 500],
        ];
    }

    /**
     * Gets query for [[VoteData]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVoteData()
    {
        return $this->hasMany(VoteData::class, ['id_contest_main' => 'id']);
    }

    /**
     * Gets an array with child data
     * @return array
     */
    public function getVoteDataByNomination()
    {        
        $query = $this->voteData;
        
        $result = [];
        foreach($query as $item) {
            $result[$item->nomination][] = $item;
        }
        return $result;
    }

    /**
     * Is voting time
     * @return boolean
     */
    public function isDateVote()
    {
        $dateStart = strtotime($this->date_start);
        $dateEnd = strtotime($this->date_end);
        $now = time();
        return $now >= $dateStart && $now <= $dateEnd;
    }

    /**
     * Check the authorization of the current user for voting
     * @return boolean
     */
    public function isAuthorizeVote()
    {
        $roles = explode('|', $this->groups_vote);
        if ($roles) {
            foreach($roles as $role) {
                if (Yii::$app->user->can($role)) {
                    return true;
                }
            }
        }
        return false;
    }


}
