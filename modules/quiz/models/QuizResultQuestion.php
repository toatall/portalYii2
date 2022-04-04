<?php

namespace app\modules\quiz\models;

use app\behaviors\AuthorBehavior;
use app\behaviors\DatetimeBehavior;
use app\models\User;
use Yii;

/**
 * This is the model class for table "{{%quiz_result_question}}".
 *
 * @property int $id
 * @property int $id_result
 * @property int $id_question
 * @property string $value
 * @property string $date_create
 * @property string $author
 *
 * @property QuizQuestion $question
 * @property User $author0
 * @property QuizResult $result
 */
class QuizResultQuestion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%quiz_result_question}}';
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
                'author_at' => 'author',
            ],              
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_result', 'id_question', 'value'], 'required'],
            [['id_result', 'id_question'], 'integer'],
            [['value'], 'string'],
            [['date_create'], 'safe'],
            [['author'], 'string', 'max' => 250],
            [['id_question'], 'exist', 'skipOnError' => true, 'targetClass' => QuizQuestion::class, 'targetAttribute' => ['id_question' => 'id']],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author' => 'username']],
            [['id_result'], 'exist', 'skipOnError' => true, 'targetClass' => QuizResult::class, 'targetAttribute' => ['id_result' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_result' => 'Id Result',
            'id_question' => 'Id Question',
            'value' => 'Value',
            'date_create' => 'Date Create',
            'author' => 'Author',
        ];
    }

    /**
     * Gets query for [[Question]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(QuizQuestion::class, ['id' => 'id_question']);
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
     * Gets query for [[Result]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResult()
    {
        return $this->hasOne(QuizResult::class, ['id' => 'id_result']);
    }
}
