<?php

namespace app\modules\executetasks\models;

use app\behaviors\AuthorBehavior;
use app\behaviors\DatetimeBehavior;
use Yii;
use yii\web\ServerErrorHttpException;

/**
 * This is the model class for table "p_execute_tasks_detail".
 *
 * @property int $id
 * @property int $id_task
 * @property string $name
 * @property int|null $count_tasks
 * @property int|null $finish_tasks
 * @property string $date_create
 * @property string $date_update
 * @property string $author
 *
 * @property ExecuteTasks $task
 */
class ExecuteTasksDetail extends \yii\db\ActiveRecord
{

    private $_department;
    private $_organization;
    private $_period;
    private $_periodYear;

    public function settings($department, $organization, $period, $periodYear)
    {
        $this->_department = $department;
        $this->_organization = $organization;
        $this->_period = $period;
        $this->_periodYear = $periodYear;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%execute_tasks_detail}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [            
            [
                'class' => DatetimeBehavior::class,
                'createdAtAttribute' => 'date_create',
                'updatedAtAttribute' => 'date_update',
            ],
            ['class' => AuthorBehavior::class],        
        ];
    }
    

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_task', 'name', 'count_tasks', 'finish_tasks'], 'required'],
            [['id_task', 'count_tasks', 'finish_tasks'], 'integer'],
            [['date_create', 'date_update'], 'safe'],
            [['name'], 'string', 'max' => 2000],
            [['author'], 'string', 'max' => 250],
            [['id_task'], 'exist', 'skipOnError' => true, 'targetClass' => ExecuteTasks::class, 'targetAttribute' => ['id_task' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_task' => 'Id Task',
            'name' => 'Наименование',
            'count_tasks' => 'Количество задач',
            'finish_tasks' => 'Количество завершенных задач',
            'date_create' => 'Date Create',
            'date_update' => 'Date Update',
            'author' => 'Author',
        ];
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(ExecuteTasks::class, ['id' => 'id_task']);
    }

    /**
     * {@inheritdoc}
     */
    public function beforeValidate()
    {
        if (!parent::beforeValidate()) {
            return false;
        }

        if (!$this->isNewRecord) {
            return true;
        }

        if (!$this->checkParentBySettings()) {
            if (!$this->createParentBySettings()) {
                throw new ServerErrorHttpException('Не удалось создать родительскую задачу');
            }
        }

        return true;
    }

    /**
     * Проверка наличия родителя
     * @return bool
     */
    private function checkParentBySettings()
    {
        $model = ExecuteTasks::findTaskByParmas($this->_department, $this->_organization, $this->_period, $this->_periodYear);
        if ($model !== null) {
            $this->id_task = $model->id;
            return true;
        }
        return false;
    }

    private function createParentBySettings()
    {
        $model = new ExecuteTasks([
            'id_department' => $this->_department,
            'org_code' => $this->_organization,
            'period' => $this->_period,
            'period_year' => $this->_periodYear,
        ]);
        if (!$model->save()) {
            return false;
        }
        $this->id_task = $model->id;
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->countersUpdate();
    }

    /**
     * {@inheritdoc}
     */
    public function afterDelete()
    {
        $this->countersUpdate();
        parent::afterDelete();
    }

    private function countersUpdate()
    {
        Yii::$app->db->createCommand("
            update {{%execute_tasks}}
            set 
                count_tasks = (select sum(count_tasks) from {{%execute_tasks_detail}} where id_task = :id2),                
                finish_tasks = (select sum(finish_tasks) from {{%execute_tasks_detail}} where id_task = :id3)
            where id = :id1
        ", [
            ':id1' => $this->id_task,
            ':id2' => $this->id_task,
            ':id3' => $this->id_task,
        ])->execute();
    }



}
