<?php

namespace app\modules\executetasks\models;

use app\behaviors\AuthorBehavior;
use app\behaviors\DatetimeBehavior;
use app\models\department\Department;
use app\models\Organization;
use app\models\User;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "p_execute_tasks".
 *
 * @property int $id
 * @property string $org_code
 * @property int $id_department
 * @property string $period
 * @property int $period_year
 * @property float|null $count_tasks
 * @property float|null $finish_tasks
 * @property string $date_create
 * @property string $date_update
 * @property string|null $log_change
 * @property string $author
 *
 * @property Department $department
 * @property Organization $orgCode
 * @property User $author0
 * @property ExecuteTasksDetail[] $executeTasksDetails
 */
class ExecuteTasks extends \yii\db\ActiveRecord
{


    private static $periods = [        
        // кварталы
        '1' => '1 квартал',
        '2' => '2 квартал',
        '3' => '3 квартал',
        '4' => '4 квартал',        
    ];

    /**
     * Периоды
     * @return array
     */
    public static function periods()
    {
        return self::$periods;
    }

    /**
     * Периоды (годы)
     * @return array
     */
    public static function periodsYears()
    {
        $years = [];
        $currentY = date('Y');
        for ($i=$currentY-3; $i<=$currentY; $i++) {
            $years[$i] = $i;
        }
        return $years;
    }


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%execute_tasks}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['org_code', 'id_department', 'period', 'period_year'], 'required'],
            [['id_department', 'period_year'], 'integer'],
            [['count_tasks', 'finish_tasks'], 'number'],
            [['date_create', 'date_update'], 'safe'],
            [['log_change'], 'string'],
            [['org_code'], 'string', 'max' => 5],
            [['period'], 'string', 'max' => 30],
            [['author'], 'string', 'max' => 250],
            [['id_department'], 'exist', 'skipOnError' => true, 'targetClass' => Department::class, 'targetAttribute' => ['id_department' => 'id']],
            [['org_code'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::class, 'targetAttribute' => ['org_code' => 'code']],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author' => 'username']],
        ];
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
     * Gets query for [[Department]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(Department::class, ['id' => 'id_department']);
    }

    /**
     * Gets query for [[OrgCode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrgCode()
    {
        return $this->hasOne(Organization::class, ['code' => 'org_code']);
    }

    /**
     * Gets query for [[Author0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor0()
    {
        return $this->hasOne(User::class, ['username' => 'author']);
    }

    /**
     * Gets query for [[ExecuteTasksDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExecuteTasksDetails()
    {
        return $this->hasMany(ExecuteTasksDetail::class, ['id_task' => 'id']);
    }


    /**
     * Наименование роли модератора
     * @return string
     */
    public static function roleModerator()
    {
        $role = Yii::$app->params['modules']['executeTasks']['roles']['moderator'] ?? null;
        if ($role == null) {
            $role = 'execute-tasks.moderator';
        }
        return $role;
    }

    /**
     * Является ли пользователь модератором
     * @return boolean
     */
    public static function isModerator()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        if (Yii::$app->user->can('admin') || Yii::$app->user->can(self::roleModerator())) {
            return true;
        }
        return false;
    }

    /**
     * Список отделов для задач
     * @return array
     */
    public static function dropDownDepartments()
    {
        $query = Department::find()->where(['not', ['department_index' => '01']])->orderBy(['department_index' => SORT_ASC])->all();
        return ArrayHelper::map($query, 'id', 'concatened');
    }

    /**
     * Список организаций
     * @return
     */
    public static function dropDownOrganizations()
    {
        return Organization::getDropDownList();
    }

    /**
     * Поиск родительской задачи по реквизитам
     * @param int $department
     * @param string $organization
     * @param string $period
     * @param int $periodYear
     * @return ExecuteTasks|null
     */
    public static function findTaskByParmas($department, $oranization, $period, $periodYear)
    {
        return self::find()->where([
            'id_department' => $department,
            'org_code' => $oranization,
            'period' => $period,
            'period_year' => $periodYear,
        ])->one();
    }


}
