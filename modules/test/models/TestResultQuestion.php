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
 * @property string $input_answers
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
            [['date_create', 'input_answers'], 'safe'],
            [['id_test_result'], 'exist', 'skipOnError' => true, 'targetClass' => TestResult::class, 'targetAttribute' => ['id_test_result' => 'id']],
            [['id_test_question'], 'exist', 'skipOnError' => true, 'targetClass' => TestQuestion::class, 'targetAttribute' => ['id_test_question' => 'id']],
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
        return $this->hasMany(TestResultAnswer::class, ['id_test_result_question' => 'id']);
    }

    /**
     * Gets query for [[TestResult]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTestResult()
    {
        return $this->hasOne(TestResult::class, ['id' => 'id_test_result']);
    }

    /**
     * Gets query for [[TestQuestion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTestQuestion()
    {
        return $this->hasOne(TestQuestion::class, ['id' => 'id_test_question']);
    }        

    /**
     * Проверка правильно ли отвечен вопрос
     */
    public function checkRightAnswer()
    {
        // определение общего веса 
        $modelQuestion = $this->testQuestion;

        if ($this->testQuestion->type_question != TestQuestion::TYPE_QUSTION_INPUT) {                    
            $query = (new Query())
                ->from('{{%test_answer}} test_answer')
                ->leftJoin('{{%test_result_answer}} test_result_answer', 'test_result_answer.id_test_answer=test_answer.id')   
                ->where([
                    'test_answer.id_test_question' => $this->id_test_question,
                    'test_result_answer.id_test_result_question' => $this->id,
                ])
                ->select('test_answer.id as id_answer, test_answer.weight, test_result_answer.id as id_answer_result')
                ->all();

            // Выполняется проверка по каждому ответу 
            // 1. Если ответ правильный и пользователь тоже выбрал этот ответ то результат увеличивается на 1
            // 2. Если ответ не правильный и пользователь выбрал этот ответ, то уменьшается на 1
            $weight = 0;
            foreach ($query as $item) {
                if ($item['weight'] > 0 && $item['id_answer_result'] != null) {
                    $weight += 1;
                }
                elseif ($item['id_answer_result'] != null) {
                    $weight -= 1;
                }
            }
            $this->is_right = (int)($weight == $modelQuestion->weight);
        }
        $this->save();
    }

    /**
     * @todo move to quiz
     */
    public function getRightAnswerId()
    {
        return (new Query())
            ->from('{{%test_answer}}')
            ->where([
                'id_test_question' => $this->id_test_question,                
            ])
            ->andWhere(['>', 'weight', '0'])
            ->select('id, name')
            ->one();        
    }

    /**
     * Преобразование ответов пользователя из json в читаемый текст
     * @return string
     */
    public function unpackUserInput()
    {
        $jsonQuestion = [];
        if ($this->testQuestion->input_answers) {
            $jsonQuestion = json_decode($this->testQuestion->input_answers, true); 
            $jsonQuestion = isset($jsonQuestion['answers']) ? $jsonQuestion['answers'] : [];       
        }
        $json = json_decode($this->input_answers);
        $result = '';
        if (is_array($json)) {
            for ($i=0; $i<count($json);$i++) {
                $result .= (isset($jsonQuestion[$i]['label']) ? $jsonQuestion[$i]['label'] . ': ' : '') . $json[$i] . '<br />';
            }           
        }
        return $result;
    }

}
