<?php

namespace app\modules\events\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "{{%contest_arts_vote}}".
 *
 * @property int $id
 * @property int $id_contest_arts
 * @property string $author
 * @property string|null $date_create
 * @property int $rating_real_art
 * @property int $rating_original_name
 *
 * @property ContestArts $contestArts
 */
class ContestArtsVote extends \yii\db\ActiveRecord
{
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%contest_arts_vote}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_contest_arts', 'type_vote', 'author', 'rating_real_art', 'rating_original_name'], 'required'],
            [['id_contest_arts', 'type_vote'], 'integer'],
            [['date_create'], 'safe'],
            [['author'], 'string', 'max' => 250],
            [['id_contest_arts'], 'exist', 'skipOnError' => true, 'targetClass' => ContestArts::className(), 'targetAttribute' => ['id_contest_arts' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_contest_arts' => 'Id Contest Arts',            
            'author' => 'Author',
            'date_create' => 'Date Create',
            'rating_real_art' => 'Rating',
            'rating_original_name' => 'Rating',
        ];
    }

    /**
     * Gets query for [[ContestArts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContestArts()
    {
        return $this->hasOne(ContestArts::className(), ['id' => 'id_contest_arts']);
    }
//       
//    /**
//     * Голосовал уже?
//     * @param integer $id
//     * @param integer $type
//     * @return array|boolean
//     */
//    public static function isVoted($id, $type)
//    {
//        return (new \yii\db\Query())
//            ->from(self::tableName())
//            ->where([
//                'id_contest_arts' => $id,
//                'type_vote' => $type,
//                'author' => Yii::$app->user->identity->username,
//            ])
//            ->one();
//    }
//    
//    public static function vote($id, $type)
//    {
//        $query = (new Query())
//            ->from(self::tableName())
//            ->where([
//                'id_contest_arts' => $id,
//                'type_vote' => $type,
//                'author' => Yii::$app->user->identity->username,
//            ])
//            ->exists();
//        
//        if ($query) {
//            Yii::$app->db->createCommand()
//                ->delete(self::tableName(), [
//                    'id_contest_arts' => $id,
//                    'type_vote' => $type,
//                    'author' => Yii::$app->user->identity->username,
//                ]);
//        }
//           
//    }
    
    
}
