<?php

namespace app\models\vote;

use app\helpers\Log\LogHelper;
use Yii;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%vote_main}}".
 *
 * @property int $id
 * @property string $name
 * @property string $date_start
 * @property string $date_end
 * @property string $organizations
 * @property int|null $multi_answer
 * @property int|null $on_general_page
 * @property string|null $description
 * @property string $date_create
 * @property string $date_edit
 * @property string $log_change
 * @property integer $count_answers
 *
 * @property VoteQuestion[] $voteQuestions
 */
class VoteMain extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%vote_main}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'date_start', 'date_end', 'organizations'], 'required'],
            [['date_start', 'date_end', 'date_create', 'date_edit', 'orgList'], 'safe'],
            [['multi_answer', 'on_general_page', 'count_answers'], 'integer'],
            [['description', 'log_change'], 'string'],
            [['name'], 'string', 'max' => 250],
            [['organizations'], 'string', 'max' => 100],
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
            'organizations' => 'Организации',
            'multi_answer' => 'Отвечать на все вопросы?',
            'on_general_page' => 'На главной странице',
            'description' => 'Описание',
            'date_create' => 'Дата создания',
            'date_edit' => 'Дата изменения',
            'log_change' => 'Журнал изменений',
            'count_answers' => 'Максимальное количество ответов',
        ];
    }

    /**
     * Gets query for [[VoteQuestions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVoteQuestions()
    {
        return $this->hasMany(VoteQuestion::class, ['id_main' => 'id'])->orderBy('count_votes desc');
    }

    /**
     * {@inheritDoc}
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (!$this->isNewRecord) {
            $this->date_edit = new Expression('getdate()');
        }
        $this->log_change = LogHelper::setLog($this->log_change, ($this->isNewRecord ? 'создание' : 'изменение'));
        return parent::beforeSave($insert);
    }

    /**
     * {@inheritDoc}
     * @throws \yii\base\InvalidConfigException
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->date_start = Yii::$app->formatter->asDate($this->date_start);
        $this->date_end = Yii::$app->formatter->asDate($this->date_end);
    }

    /**
     * Список организаций доступных для просмотра
     * @return array
     */
    public function getOrgList()
    {
        return explode('/', $this->organizations);
    }

    /**
     * Список организаций для сохранения
     * @param array $value
     */
    public function setOrgList($value)
    {
        if (!is_array($value))
            $value = array($value);
        $this->organizations = implode('/', $value);
    }

    /**
     * Признак того, что голосование окончено
     * @return boolean
     * @throws \Exception
     */
    public function getEndVote()
    {
        $d1 = date_create($this->date_start);
        $d2 = date_create($this->date_end);
        $dNow = date_create('now');
        return ($dNow < $d1 || $dNow > $d2);
    }

    /**
     * Признак того, что пользователь уже голосовал
     * @return integer
     */
    public function getIsVoted()
    {
        $query = new Query();
        return $query->from('{{%vote_answer}} answer')
            ->innerJoin('{{%vote_question}} question', 'question.id = answer.id_question')
            ->where([
                'question.id_main' => $this->id,
                'question.user_login' => Yii::$app->user->identity->username_windows,
            ])
            ->count('answer.id');
    }

    /**
     * Всего количество голосов
     * @return integer
     */
    public function getCountAnswer()
    {
        $query = new Query();
        return $query->from('{{%vote_answer}} answer')
            ->innerJoin('{{%vote_question}} question', 'question.id = answer.id_question')
            ->where([
                'question.id_main' => $this->id,
            ])
            ->count('answer.id');
    }

    /**
     * @return bool
     */
    public function isCountVoteEnd()
    {
        if ($this->count_answers == 0) {
            return false;
        }

        $countVoted = (new Query())
            ->from('{{%vote_answer}} vote_answer')
            ->leftJoin('{{%vote_question}} vote_question', 'vote_question.id=vote_answer.id_question')
            ->where([
                'vote_answer.username' => Yii::$app->user->identity->username,
                'vote_question.id_main' => $this->id,
            ])
            ->count('vote_answer.id');

        return $countVoted >= $this->count_answers;
    }

    /**
     * Максимальное количество голосов
     * @return integer
     */
    public function getCountMax()
    {
        $query = new Query();
        return $query->from('{{%vote_question}}')
            ->where([
                'id_main' => $this->id,
            ])
            ->max('count_votes');
    }

    /**
     * @return array
     */
    public static function activeVotes()
    {
        $query = (new Query())
            ->from(self::tableName())
            ->where('getdate() between date_start and date_end')
            ->all();

        $resultQuery = '';
        foreach ($query as $item) {
            $resultQuery .= Html::tag('li', Html::a($item['name'], ['/vote/index', 'id'=>$item['id']]));
        }

        $result = [];
        if ($resultQuery != '') {
            $result = [
                'name' => '<li class="nav-header">Голосование</li>' . $resultQuery,/*
                    . '<ul class="dropdown-menu dropdown-menu-main dropdown-menu-wrap nav">' . $resultQuery . '</ul>',*/
            ];
        }
        return $result;
    }


}
