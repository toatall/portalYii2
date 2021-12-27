<?php

namespace app\modules\test\models;

use Yii;
use yii\db\Query;
use app\models\Organization;
use app\models\User;
use DateTime;
use Exception;
use yii\db\Expression;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "{{%test_result}}".
 *
 * @property int $id
 * @property int $id_test
 * @property string $username
 * @property string $org_code
 * @property string $date_create
 * @property int $status
 * @property int $seconds
 * @property boolean $is_checked
 *
 * @property Test $test
 * @property Organization $orgCode
 * @property User $username0
 * @property TestResultQuestion[] $testResultQuestions
 * @property TestResultOpinion $testResultOpinion
 */
class TestResult extends \yii\db\ActiveRecord
{
    // статусы теста: выполняется сдача, сдан, отменен
    const STATUS_START = 0;
    const STATUS_FINISH = 1;
    const STATUS_CANCEL = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%test_result}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_test', 'username', 'org_code'], 'required'],
            [['id_test', 'status', 'seconds'], 'integer'],
            [['date_create'], 'safe'],
            [['username'], 'string', 'max' => 250],
            [['org_code'], 'string', 'max' => 5],
            [['id_test'], 'exist', 'skipOnError' => true, 'targetClass' => Test::class, 'targetAttribute' => ['id_test' => 'id']],
            [['org_code'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::class, 'targetAttribute' => ['org_code' => 'code']],
            [['username'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['username' => 'username_windows']],
        ];
    }

    /**
     * Gets query for [[Test]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTest()
    {
        return $this->hasOne(Test::class, ['id' => 'id_test']);
    }

    /**
     * Gets query for [[OrgCode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrgCode()
    {
        return $this->hasOne(Organization::class, ['code' => 'org_code']);
    }
    

    /**
     * Gets query for [[TestResultQuestions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTestResultQuestions()
    {
        return $this->hasMany(TestResultQuestion::class, ['id_test_result' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestResultOpinion()
    {
        return $this->hasOne(TestResultOpinion::class, ['author' => 'username'])
            ->where(['id_test' => $this->id_test]);
    }

    /**
     * @param $id
     * @return int|string
     */
    public static function countUserAttempts($id)
    {
        $query = new Query();
        return $query->from('{{%test_result}}')
            ->where([
                'id_test' => $id,
                'username' => Yii::$app->user->identity->username,
                'status' => self::STATUS_FINISH,
            ])
            ->count();
    }

    /**
     * Удаление всех результатов ответов
     */
    public function deleteAnswers()
    {
        foreach ($this->testResultQuestions as $question) {
            $question->unlinkAll('testResultAnswers', true);
        }
    }

    /**
     * Сохранение ответов из переданного массива
     * @param array $data
     */
    public function saveAnswers($data) 
    {        
        foreach ($data as $id => $values) {                        
            if (!is_array($values)) {
                $values = [$values];
            }
            
            /** @var TestResultQuestion $question */
            $question = $this->getTestResultQuestions()->where(['id_test_question' => $id])->one();
            if ($question !== null) {
                if ($question->testQuestion->type_question == TestQuestion::TYPE_QUSTION_INPUT) {
                    $question->input_answers = json_encode($values);
                }
                else {
                    foreach ($values as $value) {                    
                        (new TestResultAnswer([
                            'id_test_result_question' => $question->id,
                            'id_test_answer' => $value,
                            'date_create' => Yii::$app->formatter->asDatetime(time()),
                        ]))->save();
                    }
                }
            }
            $question->checkRightAnswer();
        }
    }   

    /**
     * Генерирование результатов теста
     * При первом переходе к решению тест для пользователя генерируются вопросы к тесту
     * (нужно для восстановления теста в случае, 
     * если выбирается ограниченное количество вопросов и пользователь по какой-либо причине прервал выполнение теста)
     */
    public function generateNewTest()
    {        
        $modelTest = $this->test;
        if ($modelTest === null) {
            throw new NotFoundHttpException();
        }
        
        $modelQuestions = TestQuestion::find()->where([
            'id_test' => $this->id_test,
        ])
        ->limit($modelTest->count_questions > 0 ? $modelTest->count_questions : false)
        ->orderBy('newid()');

        if (!empty($modelTest->use_formula_filter)) {
            $modelQuestions->andWhere($modelTest->use_formula_filter);
        }
        $modelQuestionsResult = $modelQuestions->all();      
                
        foreach ($modelQuestionsResult as $modelQuestion) {            
            (new TestResultQuestion([
                'id_test_result' => $this->id,
                'id_test_question' => $modelQuestion->id,
                'weight' => $modelQuestion->weight,
                'is_right' => null,
                'date_create' => Yii::$app->formatter->asDatetime(time()),
            ]))
            ->save();
        }
    }

    /**
     * Время сдачи теста (в секундах)
     * @return int
     */
    public function getTestLimitSeconds()
    {
        if ($this->test->time_limit == null) {
            return 0;
        }
        $time = new DateTime($this->test->time_limit);
        $timeNull = new DateTime('00:00:00');
        $timeRes = $timeNull->diff($time);
        return $timeRes->h*60*60 + $timeRes->i*60 + $timeRes->s;
    }

    /**
     * Количество вопросов
     * @return integer
     */
    public function getCountQuestions()
    {
        return (new Query())
            ->from('{{%test_result_question}}')
            ->where([
                'id_test_result' => $this->id,
            ])
            ->count();
    }

    /**
     * Количество правильно отвеченных вопросов
     * @return integer
     */
    public function getCountRightQuestions()
    {
        return (new Query())
            ->from('{{%test_result_question}}')
            ->where([
                'id_test_result' => $this->id,
                'is_right' => 1,
            ])
            ->count();
    }

    /**
     * Продолжительность сдачи теста
     * @return string
     */
    public function getDuration()
    {
        return gmdate('H:i:s', $this->seconds);
    }


    /**
     * Получение следующего вопроса
     * @return yii\db\ActiveQuery
     */
    public function getNextQuestion()
    {    
        return TestResultQuestion::find()->where([
            'id_test_result' => $this->id,
            'is_right' => null,
        ])->one();
    }    

}
