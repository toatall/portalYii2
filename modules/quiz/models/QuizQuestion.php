<?php

namespace app\modules\quiz\models;

use app\models\User;
use Yii;

/**
 * This is the model class for table "{{%quiz_question}}".
 *
 * @property int $id
 * @property int $id_quiz
 * @property int $type_question
 * @property string $name
 * @property string $date_create
 * @property string $date_update
 * @property string $author
 *
 * @property Quiz $quiz
 * @property User $author0
 * @property QuizResultQuestion[] $quizResultQuestions
 */
class QuizQuestion extends \yii\db\ActiveRecord
{

    const TYPE_STARS = 1;
    const TYPE_INPUT = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%quiz_question}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_quiz', 'name', 'type_question'], 'required'],
            [['id_quiz', 'type_question'], 'integer'],
            [['date_create', 'date_update'], 'safe'],
            [['name'], 'string', 'max' => 2000],
            [['author'], 'string', 'max' => 250],
            [['id_quiz'], 'exist', 'skipOnError' => true, 'targetClass' => Quiz::class, 'targetAttribute' => ['id_quiz' => 'id']],
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
            'id_quiz' => 'Id Quiz',
            'name' => 'Name',
            'date_create' => 'Date Create',
            'date_update' => 'Date Update',
            'author' => 'Author',
        ];
    }

    /**
     * Gets query for [[Quiz]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuiz()
    {
        return $this->hasOne(Quiz::class, ['id' => 'id_quiz']);
    }

    /**
     * Gets query for [[Author0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor0()
    {
        return $this->hasOne(User::class, ['username' => 'author']);
    }

    /**
     * Gets query for [[QuizResultQuestions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuizResultQuestions()
    {
        return $this->hasMany(QuizResultQuestion::className(), ['id_question' => 'id']);
    }
}
