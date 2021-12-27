<?php

namespace app\modules\test\models;

use Yii;
use app\models\User;
use stdClass;
use yii\db\Query;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "{{%test_question}}".
 *
 * @property int $id
 * @property int $id_test
 * @property string $name
 * @property int|null $type_question
 * @property string $input_answers
 * @property string|null $attach_file
 * @property int|null $weight
 * @property string $date_create
 * @property string $author
 *
 * @property TestAnswer[] $testAnswers
 * @property Test $test
 * @property User $author0
 * @property TestResultQuestion[] $testResultQuestions
 */
class TestQuestion extends \yii\db\ActiveRecord
{
    /**
     * Выбор только 1 варианта
     */
    const TYPE_QUESTION_RADIO = 0;

    /**
     * Выбор нескольких вариантов ответов
     */
    const TYPE_QUESTION_CHECK = 1;

    /**
     * Ввод ответа пользователем
     */
    const TYPE_QUSTION_INPUT = 2;    

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%test_question}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_test', 'name', 'author'], 'required'],
            [['id_test', 'type_question', 'weight'], 'integer'],
            [['date_create', 'input_answers'], 'safe'],
            [['name'], 'string', 'max' => 2500],
            [['attach_file'], 'string', 'max' => 200],
            [['author'], 'string', 'max' => 250],
            [['id_test'], 'exist', 'skipOnError' => true, 'targetClass' => Test::class, 'targetAttribute' => ['id_test' => 'id']],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author' => 'username_windows']],           
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'id_test' => 'Тест',
            'name' => 'Наименование',
            'type_question' => 'Тип вопроса',
            'input_answers' => 'Формат ответов',
            'attach_file' => 'Файл',
            'weight' => 'Количество баллов',
            'date_create' => 'Дата создания',
            'author' => 'Автор',
            'uploadFiles' => 'Приложения',
            'deleteFiles' => 'Отметьте приложения для удаления',
        ];
    }

    /**
     * Gets query for [[TestAnswers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTestAnswers()
    {
        return $this->hasMany(TestAnswer::class, ['id_test_question' => 'id']);
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
     * Gets query for [[Author0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor0()
    {
        return $this->hasOne(User::class, ['username_windows' => 'author']);
    }

    /**
     * Gets query for [[TestResultQuestions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTestResultQuestions()
    {
        return $this->hasMany(TestResultQuestion::class, ['id_test_question' => 'id']);
    }
    
    /**
     * {@inheritdoc}
     * @return bool     
     */
    public function beforeValidate() 
    {
        $this->author = Yii::$app->user->identity->username;
        return parent::beforeValidate();        
    }

    /**
     * {@inheritdoc}
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->test->show_right_answer) {
            $this->type_question = self::TYPE_QUESTION_RADIO;
        }
        return true;
    }

    /**
     * @return array
     */
    public function parseInputAnsewrs()
    {        
        $json = json_decode($this->input_answers);                
        if ($json instanceof stdClass  && isset($json->answers) && is_array($json->answers)) {
            return $json->answers;
        }
        return [];
    }

}
