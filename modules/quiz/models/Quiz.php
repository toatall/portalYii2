<?php

namespace app\modules\quiz\models;

use app\models\User;
use Yii;

/**
 * This is the model class for table "{{%quiz}}".
 *
 * @property int $id
 * @property int $id_quiz
 * @property string $name
 * @property string $date_create
 * @property string $date_update
 * @property string $author
 *
 * @property User $authorModel
 * @property QuizQuestion[] $quizQuestions
 * @property QuizResult $resultMy
 */
class Quiz extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%quiz}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'id_quiz'], 'required'],
            [['date_create', 'date_update'], 'safe'],
            [['name'], 'string', 'max' => 2000],
            [['author'], 'string', 'max' => 250],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author' => 'username']],
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
            'author' => 'Author',
        ];
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
     * Gets query for [[QuizQuestions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuizQuestions()
    {
        return $this->hasMany(QuizQuestion::class, ['id_quiz' => 'id']);
    }


    public function getResultMy()
    {
        return $this->hasOne(QuizResult::class, ['id_quiz' => 'id'])
            ->where(['username' => Yii::$app->user->identity->username]);
    }

}
