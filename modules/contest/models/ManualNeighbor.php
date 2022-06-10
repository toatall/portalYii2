<?php

namespace app\modules\contest\models;

use app\models\department\Department;
use Yii;
use yii\db\Expression;
use yii\db\Query;

/**
 * This is the model class for table "p_contest_manual_neighbor".
 *
 * @property int $id
 * @property string $name
 * @property string $department
 * @property string|null $file
 * @property string $date_create
 * @property int $count_votes_1
 * @property int $count_votes_2
 * @property int $count_votes_3
 *
 * @property ManualNeighborVote[] $manualNeighborVotes
 */
class ManualNeighbor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%contest_manual_neighbor}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'department'], 'required'],
            [['date_create'], 'safe'],
            [['name', 'file'], 'string', 'max' => 500],
            [['department'], 'string', 'max' => 250],
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
            'department' => 'Отдел',
            'file' => 'Файл',
            'date_create' => 'Date Create',
        ];
    }

    /**
     * Gets query for [[ManualNeighborVotes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getManualNeighborVotes()
    {
        // /return $this->hasMany(ManualNeighborVote::className(), ['id_manual_neighbor' => 'id']);
    }

    public function afterFind()
    {
        if ($this->count_votes_1 === null) {
            $this->count_votes_1 = 0;
        }
        if ($this->count_votes_2 === null) {
            $this->count_votes_2 = 0;
        }
        if ($this->count_votes_3 === null) {
            $this->count_votes_3 = 0;
        }
    }

    /**
     * Проверка может ли текущий пользователь голосовать
     * @return bool
     */
    public static function isCanVoted()
    {
        return false;// 10.06.2022 закрыто голосование
        if (Yii::$app->user->isGuest) {
            return false;            
        }
        if (!Yii::$app->user->identity->isOrg('8600')) {
            return false;
        }
        if ((new Query())
            ->from('{{%contest_manual_neighbor_vote}}')
            ->where(['username' => Yii::$app->user->identity->username])
            ->exists()) {
                return false;
            }
        return true;
    }

    public static function dropDownDepartments()
    {
        return array_map(function($item) {
            if ($item['department_name'] != Yii::$app->user->identity->department) {
                return [$item['department_name'] => $item['department_name']];
            }
        }, Department::find()->all());

    }

    /**
     * @return yii\db\Query
     */
    public static function withoutDepartmentCurrentUser()
    {
        return parent::find()
            ->andWhere(['not', ['department' => Yii::$app->user->identity->department]]);
    }

    /**
     * @return array
     */
    public static function getItemsForRadio()
    {
        $res = [];
        $rows = self::withoutDepartmentCurrentUser()->all();
        foreach($rows as $item) {
            $res[$item->id] = '<a href="' . $item->file . '" target="_blank" data-pjax="false">' . $item->name . '</a> (<i class="far fa-building"></i> ' . $item->department . ')';
        }
        return $res;
    }

    /**
     * Голоса текущего пользователя
     * @return array|null
     */
    public static function getMyVote()
    {
        return (new Query())
            ->from('{{%contest_manual_neighbor_vote}}')
            ->where(['username' => Yii::$app->user->identity->username])
            ->one();
    }

    /**
     * @return int
     */
    public static function saveVote($data)
    {
        $res = Yii::$app->db->createCommand()
            ->insert('{{%contest_manual_neighbor_vote}}', [
                'id_manual_neighbor_1' => $data['vote1'],
                'id_manual_neighbor_2' => $data['vote2'],
                'id_manual_neighbor_3' => $data['vote3'],
                'username' => Yii::$app->user->identity->username,
                'date_create' => new Expression('getdate()'),
            ])
            ->execute();

        self::updateCounts();

        return $res;
    }

    private static function updateCounts()
    {
        Yii::$app->db->createCommand("         
            update t
                set
                    t.count_votes_1 = (select count(id) from {{%contest_manual_neighbor_vote}} where id_manual_neighbor_1 = t.id),
                    t.count_votes_2 = (select count(id) from {{%contest_manual_neighbor_vote}} where id_manual_neighbor_2 = t.id),
                    t.count_votes_3 = (select count(id) from {{%contest_manual_neighbor_vote}} where id_manual_neighbor_3 = t.id)
            from {{%contest_manual_neighbor}} t
        ")->execute();
    }

    

}
