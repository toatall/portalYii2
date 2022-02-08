<?php

namespace app\modules\rookie\modules\fortboyard\models;

use app\helpers\DateHelper;
use Yii;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%fort_boyard}}".
 *
 * @property int $id
 * @property int $id_team
 * @property string $title
 * @property string|null $text
 * @property string|null $date_create
 * @property string $date_show_1
 * @property string $date_show_2
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
            [['id_team', 'date_show_1', 'date_show_2', 'title'], 'required'],
            [['id_team'], 'integer'],
            [['date_show', 'date_create'], 'safe'],
            [['text'], 'string'],
            [['title'], 'string', 'max' => 250],               
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
            'date_show_1' => 'Дата показа от',
            'date_show_2' => 'Дата показа до',
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
        return self::find()
            ->where(['<=', 'date_show_1', new Expression('getdate()')])
            ->andWhere(['>=', 'date_show_2', new Expression('getdate()')])
            ->one();
    }

    /**
     * Имя команды
     * @return string|null
     */
    public function getTeamName()
    {
        return (new Query())
            ->from('{{%fort_boyard_teams}}')
            ->where(['id' => $this->id_team])
            ->select('name')
            ->one()['name'] ?? null;
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
                't.id_fort_boyard' => $this->id,
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
        $this->date_show_1 = $this->date_show_1 
            ? Yii::$app->formatter->asDatetime($this->date_show_1) : $this->date_show_1;
        $this->date_show_2 = $this->date_show_2 
            ? Yii::$app->formatter->asDatetime($this->date_show_2) : $this->date_show_2;
    }

    /**
     * Может ли пользователь голосовать
     * @param int $idTeam
     * @return bolean
     */
    public static function canVoid(int $idTeam) : bool
    {       
        if (!Yii::$app->user->identity->isOrg('8600')) {
            return false;
        }

        if (DateHelper::dateDiffDays('11.02.2022', 'today') <= 0) {
            return false;
        }

        if ((new Query())
            ->from('{{%fort_boyard_team_vote}}')
            ->where([
                'id_team' => $idTeam,
                'username' => Yii::$app->user->identity->username,
                ])
            ->exists()) {
                return false;
            }
        
        // пользователь не должен являеться сотрудником отдела команды
        return (new Query())
            ->from('{{%fort_boyard_access}} t')
            ->leftJoin('{{%user}} u', 't.username = u.username')
            ->where(['t.id_team' => $idTeam])
            ->andWhere(['not', ['u.department' => Yii::$app->user->identity->department]])
            ->exists();

    }
    
}
