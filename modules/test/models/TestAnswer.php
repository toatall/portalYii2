<?php

namespace app\modules\test\models;

use Yii;

/**
 * This is the model class for table "{{%test_answer}}".
 *
 * @property int $id
 * @property int $id_test_question
 * @property string $name
 * @property string|null $attach_file
 * @property int|null $weight
 * @property string $date_create
 *
 * @property TestQuestion $testQuestion
 * @property TestResultAnswer[] $testResultAnswers
 */
class TestAnswer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%test_answer}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_test_question', 'name'], 'required'],
            [['id_test_question', 'weight'], 'integer'],
            [['date_create'], 'safe'],
            [['name'], 'string', 'max' => 2500],
            [['attach_file'], 'string', 'max' => 200],
            [['id_test_question'], 'exist', 'skipOnError' => true, 'targetClass' => TestQuestion::class, 'targetAttribute' => ['id_test_question' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'id_test_question' => 'Вопрос',
            'name' => 'Наименование',
            'attach_file' => 'Файл',
            'file' => 'Файл',
            'weight' => 'Баллы за ответ',
            'date_create' => 'Дата создания',
            'delFile' => 'Удалить файл',
        ];
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
     * Gets query for [[TestResultAnswers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTestResultAnswers()
    {
        return $this->hasMany(TestResultAnswer::className(), ['id_test_answer' => 'id']);
    }
}
