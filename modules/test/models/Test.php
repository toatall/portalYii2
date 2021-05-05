<?php

namespace app\modules\test\models;

use Yii;
use app\models\User;

/**
 * This is the model class for table "{{%test}}".
 *
 * @property int $id
 * @property string $name
 * @property string $date_start
 * @property string $date_end
 * @property int|null $count_attempt
 * @property int|null $count_questions
 * @property string|null $description
 * @property string|null $time_limit
 * @property string $date_create
 * @property string $author
 *
 * @property User $author0
 * @property TestQuestion[] $testQuestions
 * @property TestResult[] $testResults
 *
 * @property bool $active
 */
class Test extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%test}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'date_start', 'date_end', 'author'], 'required'],
            [['date_start', 'date_end', 'time_limit', 'date_create'], 'safe'],
            [['count_attempt', 'count_questions'], 'integer'],
            [['description'], 'string'],
            [['name', 'author'], 'string', 'max' => 250],
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
            'name' => 'Наименование',
            'date_start' => 'Дата начала',
            'date_end' => 'Дата окончания',
            'count_attempt' => 'Количество попыток',
            'count_questions' => 'Количество вопросов (доступные при ответах)',
            'description' => 'Описание',
            'time_limit' => 'Ограничение по времени',
            'date_create' => 'Дата создания',
            'author' => 'Автор',
        ];
    }

    /**
     * {@inheritDoc}
     * @return bool
     */
    public function beforeValidate()
    {
        $this->author = Yii::$app->user->identity->username;
        return parent::beforeValidate();
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
     * Gets query for [[TestQuestions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTestQuestions()
    {
        return $this->hasMany(TestQuestion::class, ['id_test' => 'id']);
    }

    /**
     * Gets query for [[TestResults]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTestResults()
    {
        return $this->hasMany(TestResult::class, ['id_test' => 'id']);
    }

    /**
     * @return bool
     */
    public function getActive()
    {
        $dateNow = time();
        $dateStart = strtotime($this->date_start);
        $dateEnd = strtotime($this->date_end);
        return $dateNow >= $dateStart && $dateNow <= $dateEnd;
    }

    /**
     * Проверят есть ли доступ на просмотр статистики
     * @return bool
     */
    public function isViewStatistic()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        if (Yii::$app->user->can('admin')) {
            return true;
        }

        $accounts = Yii::$app->params['test']['access']['viewStatistic'];

        // поиск по имени учетной записи
        if (in_array(Yii::$app->user->identity->username, $accounts)) {
            return true;
        }

        /*
        // поиск по группам
        foreach ($accounts as $account) {
            if (in_array($account, UserInfo::inst()->ADMemberOf)) {
                return true;
            }
        }
        */

        return false;
    }

    /**
     * Количество времени в секундах
     * @return false|float|int|string
     */
    public function getTimeLimitSeconds()
    {
        if ($this->time_limit == null) {
            return 0;
        }
        $time = strtotime($this->time_limit);
        $hour = date('H', $time);
        $minute = date('i', $time);
        $seconds = date('s', $time);
        return $hour*60*60 + $minute*60 + $seconds;
    }
    
    /**
     * Рейтинг теста
     * @return int|null
     */
    public function getRatingValue()
    {
        $value = (new \yii\db\Query())
            ->from('{{%test_result_opinion}}')
            ->where([
                'id_test' => $this->id,
            ])
            ->select('avg(cast(rating as float))')
            ->scalar();
        return round($value, 2);
    }

}
