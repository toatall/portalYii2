<?php

namespace app\modules\test\models;

use Yii;

/**
 * This is the model class for table "{{%test_result_answer}}".
 *
 * @property int $id
 * @property int $id_test_result_question
 * @property int $id_test_answer
 * @property string $date_create
 *
 * @property TestAnswer $testAnswer
 * @property TestResultQuestion $testResultQuestion
 */
class TestResultAnswer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%test_result_answer}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_test_result_question', 'id_test_answer'], 'required'],
            [['id_test_result_question', 'id_test_answer'], 'integer'],
            [['date_create'], 'safe'],
            [['id_test_answer'], 'exist', 'skipOnError' => true, 'targetClass' => TestAnswer::class, 'targetAttribute' => ['id_test_answer' => 'id']],
            [['id_test_result_question'], 'exist', 'skipOnError' => true, 'targetClass' => TestResultQuestion::class, 'targetAttribute' => ['id_test_result_question' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_test_result_question' => 'Id Test Result Question',
            'id_test_answer' => 'Id Test Answer',
            'date_create' => 'Date Create',
        ];
    }

    /**
     * Gets query for [[TestAnswer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTestAnswer()
    {
        return $this->hasOne(TestAnswer::class, ['id' => 'id_test_answer']);
    }

    /**
     * Gets query for [[TestResultQuestion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTestResultQuestion()
    {
        return $this->hasOne(TestResultQuestion::class, ['id' => 'id_test_result_question']);
    }
}
