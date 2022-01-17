<?php

namespace app\modules\rookie\modules\fortboyard\models;

use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%fort_boyard}}".
 *
 * @property int $id
 * @property int $id_team
 * @property string $date_show
 * @property string $title
 * @property string|null $text
 * @property string|null $date_create
 *
 * @property FortBoyardTeams $team
 * @property FortBoyardAnswers[] $fortBoyardAnswers
 */
class FortBoyard extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%fort_boyard}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_team', 'date_show', 'title'], 'required'],
            [['id_team'], 'integer'],
            [['date_show', 'date_create'], 'safe'],
            [['text'], 'string'],
            [['title'], 'string', 'max' => 250],
            [['date_show'], 'unique'],           
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'id_team' => 'Команда',
            'date_show' => 'Дата показа',
            'title' => 'Заголовок',
            'text' => 'Описание',
            'date_create' => 'Дата создания',
        ];
    }

    /**
     * Получение вопроса на сегодня
     * @return FortBoyard|null
     */
    public static function todayQuestion()
    {
        return self::find()->where(['date_show' => \Yii::$app->formatter->asDate('today')])->one();
    }

    /**
     * Список команд
     * @return array
     */
    public function dropDownTeams()
    {
        $query = (new Query())
            ->from('{{%fort_boyard_teams}}')
            ->orderBy(['name' => SORT_ASC])
            ->all();
        return ArrayHelper::map($query, 'id', 'name');
    }

    /**
     * Проверка прав для ответа
     * @return boolean
     */
    public function isRight()
    {
        if (\Yii::$app->user->isGuest) {
            return false;
        }

        // 1. если текущий пользователь есть в таблице p_fort_boyard_access
        $queryAccess = (new Query())
            ->from('{{%fort_boyard_access}}')
            ->where(['username' => \Yii::$app->user->identity->username])
            ->one();

        if (!$queryAccess) {
            return false;
        }

        // 2. если текущий идентификатор команды = идентификатору текущего вопроса, то пользователь не может отвечать
        if ($queryAccess['id_team'] == $this->id_team) {
            return false;
        }

        // 3. если еще не было ответа текущей команды
        if ((new Query())
            ->from('{{%fort_boyard_answers}} t')
            ->innerJoin('{{%fort_boyard}} f', 't.id_fort_boyard = f.id')
            ->where([
                'f.id_team' => $this->id_team,
                't.username' => \Yii::$app->user->identity->username,
            ])
            ->exists()) {
            return false;
        }        

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function afterFind()
    {
        $this->date_show = Yii::$app->formatter->asDate($this->date_show);
    }
    
}
