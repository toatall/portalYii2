<?php

namespace app\modules\test\models;

use Yii;
use yii\db\Query;
use app\models\Organization;
use app\models\User;

/**
 * This is the model class for table "{{%test_result}}".
 *
 * @property int $id
 * @property int $id_test
 * @property string $username
 * @property string $org_code
 * @property string $date_create
 *
 * @property Test $test
 * @property Organization $orgCode
 * @property User $username0
 * @property TestResultQuestion[] $testResultQuestions
 */
class TestResult extends \yii\db\ActiveRecord
{
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
            [['id_test'], 'integer'],
            [['date_create'], 'safe'],
            [['username'], 'string', 'max' => 250],
            [['org_code'], 'string', 'max' => 5],
            [['id_test'], 'exist', 'skipOnError' => true, 'targetClass' => Test::className(), 'targetAttribute' => ['id_test' => 'id']],
            [['org_code'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::className(), 'targetAttribute' => ['org_code' => 'code']],
            [['username'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['username' => 'username_windows']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_test' => 'Id Test',
            'username' => 'Username',
            'org_code' => 'Org Code',
            'date_create' => 'Date Create',
        ];
    }

    /**
     * Gets query for [[Test]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTest()
    {
        return $this->hasOne(Test::className(), ['id' => 'id_test']);
    }

    /**
     * Gets query for [[OrgCode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrgCode()
    {
        return $this->hasOne(Organization::className(), ['code' => 'org_code']);
    }

    /**
     * Gets query for [[Username0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsername0()
    {
        return $this->hasOne(User::className(), ['username_windows' => 'username']);
    }

    /**
     * Gets query for [[TestResultQuestions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTestResultQuestions()
    {
        return $this->hasMany(TestResultQuestion::className(), ['id_test_result' => 'id']);
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
            ])
            ->count();
    }


    /**
     * Сохранение информации в БД
     * Вернуть результат пользователю
     * @param $result
     * @return array
     * @throws Exception
     * @throws \Exception
     */
    public function saveResult($result)
    {
        $fails = [];
        if (!isset($result['id'])) {
            $fails[] = "Не найден элемент id!";
        }
        if (!isset($result['questions'])) {
            $fails[] = "Не найден элемент questions!";
        }
        if (!isset($result['answers'])) {
            $result['answers'] = [];
        }
        if (!is_array($result['questions'])) {
            $fails[] = "Элемент questions не является массивом!";
        }
        if (!is_array($result['answers'])) {
            $result['answers'] = [];
        }

        if ($fails) {
            throw new \Exception(implode('<br />', $fails));
        }

        $model = new TestResult();
        $model->id_test = $result['id'];
        $model->username = Yii::$app->user->identity->username;
        $model->org_code = Yii::$app->userInfo->current_organization;
        if (!$model->save()) {
            $errors = implode('<br />', implode('<br />', $model->getErrors()));
            throw new \Exception('Не удалось сохранить данные о тесте. ' . $errors);
        }

        $questions = $result['questions'];
        $answers = $result['answers'];

        // Результат теста
        $result = [
            'questions' => 0,
            'rightAnswers' => 0,
            'id_test_result' => $model->id,
        ];

        // найти все вопросы
        $query = new Query();
        $resultQuery = $query->from('{{%test_question}}')
            ->where(['in', 'id', $questions])
            ->all();

        foreach ($resultQuery as $item) {
            $result['questions'] += 1;

            $modelResultQuestion = new TestResultQuestion();
            $modelResultQuestion->id_test_result = $model->id;
            $modelResultQuestion->id_test_question = $item['id'];
            $modelResultQuestion->weight = $item['weight'];
            $modelResultQuestion->save();

            $forFind = !isset($answers[$item['id']]) ? [] : $answers[$item['id']];

            $modelResultQuestion->is_right = $modelResultQuestion->saveAnswers($forFind);
            $modelResultQuestion->save(false, ['is_right']);
            $result['rightAnswers'] += $modelResultQuestion->is_right;
        }

        return $result;
    }
}
