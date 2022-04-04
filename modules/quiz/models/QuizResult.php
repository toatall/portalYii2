<?php

namespace app\modules\quiz\models;

use app\behaviors\AuthorBehavior;
use app\behaviors\DatetimeBehavior;
use app\models\User;
use Yii;

/**
 * This is the model class for table "{{%quiz_result}}".
 *
 * @property int $id
 * @property int $id_quiz
 * @property string $username
 * @property string $date_create
 *
 * @property User $username0
 * @property QuizResultQuestion[] $quizResultQuestions
 */
class QuizResult extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%quiz_result}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [            
            [
                'class' => DatetimeBehavior::class,
                'createdAtAttribute' => 'date_create',
                'updatedAtAttribute' => null,
            ],
            [
                'class' => AuthorBehavior::class,
                'author_at' => 'username',
            ],              
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_quiz'], 'required'],
            [['id_quiz'], 'integer'],
            [['date_create'], 'safe'],
            [['username'], 'string', 'max' => 250],
            [['username'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['username' => 'username']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_quiz' => 'Id Quiz',
            'username' => 'Username',
            'date_create' => 'Date Create',
        ];
    }

    /**
     * Gets query for [[Username0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsername0()
    {
        return $this->hasOne(User::class, ['username' => 'username']);
    }

    /**
     * Gets query for [[QuizResultQuestions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuizResultQuestions()
    {
        return $this->hasMany(QuizResultQuestion::class, ['id_result' => 'id']);
    }
}
