<?php

namespace app\models\vote;

use app\helpers\Log\LogHelper;
use Yii;
use yii\db\Expression;
use yii\db\Query;

/**
 * This is the model class for table "{{%vote_question}}".
 *
 * @property int $id
 * @property int $id_main
 * @property int|null $count_votes
 * @property string $text_question
 * @property string $date_create
 * @property string $date_edit
 * @property string $log_change
 * @property string $text_html
 *
 * @property VoteAnswer[] $voteAnswers
 * @property VoteMain $main
 */
class VoteQuestion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%vote_question}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_main', 'text_question'], 'required'],
            [['id_main', 'count_votes'], 'integer'],
            [['text_question', 'log_change', 'text_html'], 'string'],
            [['date_create', 'date_edit'], 'safe'],
            [['id_main'], 'exist', 'skipOnError' => true, 'targetClass' => VoteMain::class, 'targetAttribute' => ['id_main' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'id_main' => 'ИД родителя',
            'count_votes' => 'Количество голосов',
            'text_question' => 'Текст вопроса',
            'date_create' => 'Дата создания',
            'date_edit' => 'Дата изменения',
            'log_change' => 'Журнал изменений',
            'text_html' => 'Описание вопроса',
        ];
    }

    /**
     * Gets query for [[Main]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMain()
    {
        return $this->hasOne(VoteMain::class, ['id' => 'id_main']);
    }

    /**
     * {@inheritDoc}
     * @return \yii\db\ActiveQuery
     */
    public static function find()
    {
        return parent::find()->orderBy('count_votes desc, text_question asc');
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
        else {
            $this->count_votes = 0;
        }
        $this->log_change = LogHelper::setLog($this->log_change, ($this->isNewRecord ? 'создание' : 'изменение'));
        return parent::beforeSave($insert);
    }

    /**
     * Сохранение голоса
     * @param integer $idMain
     * @param integer $votes
     * @return boolean
     * @throws \yii\db\Exception
     */
    public static function saveAnswer($votes)
    {
        if (!is_array($votes)) {
            return false;
        }

        foreach ($votes as $vote) {
            if (!is_numeric($vote)) {
                continue;
            }

            Yii::$app->db->createCommand()->insert('{{%vote_answer}}', [
                'id_question'=>$vote,
                'user_login'=>Yii::$app->user->identity->username_windows,
            ])->execute();
        }
    }

    /**
     * @return bool
     */
    public function isVoted()
    {
        return (new Query())
            ->from('{{%vote_answer}}')
            ->where([
                'id_question' => $this->id,
                'username' => Yii::$app->user->identity->username,
            ])
            ->exists();
    }


}
