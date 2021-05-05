<?php

namespace app\modules\test\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "{{%test_result_question}}".
 *
 * @property int $id
 * @property int $id_test_result
 * @property int $id_test_question
 * @property int|null $weight
 * @property int|null $is_right
 * @property string $date_create
 *
 * @property TestResultAnswer[] $testResultAnswers
 * @property TestResult $testResult
 * @property TestQuestion $testQuestion
 */
class TestResultQuestion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%test_result_question}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_test_result', 'id_test_question'], 'required'],
            [['id_test_result', 'id_test_question', 'weight', 'is_right'], 'integer'],
            [['date_create'], 'safe'],
            [['id_test_result'], 'exist', 'skipOnError' => true, 'targetClass' => TestResult::className(), 'targetAttribute' => ['id_test_result' => 'id']],
            [['id_test_question'], 'exist', 'skipOnError' => true, 'targetClass' => TestQuestion::className(), 'targetAttribute' => ['id_test_question' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_test_result' => 'Id Test Result',
            'id_test_question' => 'Id Test Question',
            'weight' => 'Weight',
            'is_right' => 'Is Right',
            'date_create' => 'Date Create',
        ];
    }

    /**
     * Gets query for [[TestResultAnswers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTestResultAnswers()
    {
        return $this->hasMany(TestResultAnswer::className(), ['id_test_result_question' => 'id']);
    }

    /**
     * Gets query for [[TestResult]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTestResult()
    {
        return $this->hasOne(TestResult::className(), ['id' => 'id_test_result']);
    }

    /**
     * Gets query for [[TestQuestion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTestQuestion()
    {
        return $this->hasOne(TestQuestion::className(), ['id' => 'id_test_question']);
    }

    /**
     *
     * @param $answer string|array
     * @return int
     */
    public function saveAnswers($answer)
    {
        $query = new Query();
        $weight = $query->from('{{%test_answer}}')
            ->where(['in', 'id', $answer])
            ->sum('weight');

        $command = Yii::$app->db->createCommand();

        if (is_array($answer)) {
            // сохранение ответов в таблице
            foreach ($answer as $item) {
                $command->insert('{{%test_result_answer}}', [
                    'id_test_result_question' => $this->id,
                    'id_test_answer' => $item,
                ]);
            }
        }
        else {
            // сохранение ответов в таблице
            $command->insert('{{%test_result_answer}}', [
                'id_test_result_question' => $this->id,
                'id_test_answer' => $answer,
            ]);
        }
        return $this->weight == $weight ? 1 : 0;
    }
}
