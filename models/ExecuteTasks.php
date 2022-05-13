<?php

namespace app\models;

use app\behaviors\AuthorBehavior;
use app\behaviors\ChangeLogBehavior;
use app\behaviors\DatetimeBehavior;
use app\models\department\Department;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%execute_tasks}}".
 *
 * @property int $id
 * @property string $org_code
 * @property int $id_department
 * @property string $period
 * @property int $period_year
 * @property int|null $count_tasks
 * @property int|null $finish_tasks
 * @property string $date_create
 * @property string $date_update
 * @property string|null $log_change
 * @property string $author
 *
 * @property Department $departmentModel
 * @property Organization $orgModel
 * @property User $authorModel
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
            [['count_tasks', 'finish_tasks'], 'double'],
            [['date_create', 'date_update'], 'safe'],
            [['log_change'], 'string'],
            [['org_code'], 'number', 'min' => 8600, 'max' => 8699],
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
            ['class' => ChangeLogBehavior::class],         
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'org_code' => 'Налоговый орган',
            'id_department' => 'Отдел',
            'period' => 'Отчетный период',
            'period_year' => 'Отчетный год',
            'count_tasks' => 'Количество поставленных задач',
            'finish_tasks' => 'Количество исполненных задач',
            'date_create' => 'Дата создания',
            'date_update' => 'Дата изменения',
            'log_change' => 'Журнал изменений',
            'author' => 'Автор',
        ];
    }

    /**
     * Gets query for [[Department]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDepartmentModel()
    {
        return $this->hasOne(Department::class, ['id' => 'id_department']);
    }

    /**
     * Gets query for [[Organization]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrgModel()
    {
        return $this->hasOne(Organization::class, ['code' => 'org_code']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthorModel()
    {
        return $this->hasOne(User::class, ['username' => 'author']);
    }


    /**
     * Наименование роли модератора
     * @return string
     */
    public static function roleModerator()
    {
        $role = Yii::$app->params['executeTasks']['roles']['moderator'] ?? null;
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
     * @return ExecuteTasks[]
     */
    public static function getModelsByPeriod($department, $preiod, $periodYear)
    {
        $result = [];
        $query = Organization::getDropDownList();

        foreach ($query as $code=>$name) {
            $model = ExecuteTasks::find()
                ->where([
                    'id_department' => $department,
                    'period' => $preiod,
                    'period_year' => $periodYear,
                    'org_code' => $code,
                ])
                ->one();
            if ($model === null) {
                $model = new ExecuteTasks([
                    'org_code' => $code,
                    'id_department' => $department,
                    'period' => $preiod,
                    'period_year' => $periodYear,                        
                ]);
                if (!$model->save()) { 
                    //--                                       
                }
            }
            $result[$code] = $model;           
        }
        return $result;
    }

    /**
     * @return int
     */
    public static function getTotal($data)
    {       
        if (!$data) {
            return 0;
        }
        $totalAll = 0;
        $totalFinish = 0;
        foreach($data as $item) {
            $totalAll += $item['count_tasks'];
            $totalFinish += $item['finish_tasks'];            
        }
        if ($totalAll > 0) {
            return round($totalFinish / $totalAll * 100);
        }
        return 0;
    }

    /**
     * @param array $data
     * @return array|null
     */
    public static function getTotalWithIndex($data)
    {
        if (!$data) {
            return null;
        }

        $queryIndexes = (new Query())
            ->from('{{%execute_tasks_department}}')
            ->indexBy('id_department')
            ->all();      
        $totals = [];        
        foreach ($queryIndexes as $item) {
            $totals[$item['type_index']] = [
                'all' => 0,
                'finish' => 0,
            ];
        }
        
        if (!$totals) {
            return null;
        }
        
        foreach($data as $item) {
            if (isset($queryIndexes[$item['id_department']])) {              
                $totals[$queryIndexes[$item['id_department']]['type_index']]['all'] += $item['count_tasks'];
                $totals[$queryIndexes[$item['id_department']]['type_index']]['finish'] += $item['finish_tasks'];
            }
        }

        return $totals;
    }

    /**
     * Формирование массива для графика по отделам
     * @param array $data
     * @param string $period
     * @param string $periodYear
     * @return array
     */
    public static function getDepartments($data, $period=null, $periodYear=null, $idOrganization=null)
    {
        if (!$data) {
            return null;
        }
        $result = [];
        foreach($data as $item) {
            if ($idOrganization != null && $idOrganization != $item['org_code']) {
                continue;
            }
            $dep = $item['department_index'] . ' ' . $item['department_name'];
            if (!isset($result[$dep])) {
                $result[$dep]['all'] = 0;
                $result[$dep]['finish'] = 0;
            }
            $result[$dep]['all'] += $item['count_tasks'];
            $result[$dep]['finish'] += $item['finish_tasks'];
            $result[$dep]['url'] =  Url::to([
                '/execute-tasks/data-organization', 
                'idDepartment' => $item['department_id'],
                'period' => $period,
                'periodYear' => $periodYear,
            ]);
            $result[$dep]['full_name'] = $item['department_index'] . ' ' . $item['department_name_full'];
        }      
        return $result;
    }

    /**
     * Формирование массива для графика по организациям
     * @param array $data
     * @param string|null $period
     * @param string|null $periodYear
     * @param int|null $idDepartment для фильтрации данных
     * @return array
     */
    public static function getOrganizations($data, $period=null, $periodYear=null, $idDepartment=null)
    {
        if (!$data) {
            return null;
        }
        $result = [];
        foreach($data as $item) {            
            if ($idDepartment != null && $idDepartment != $item['id_department']) {
                continue;
            }
            $org = $item['org_code'] .  ' ' . $item['org_name'];
            if (!isset($result[$org])) {
                $result[$org]['all'] = 0;
                $result[$org]['finish'] = 0;
            }
            $result[$org]['all'] += $item['count_tasks'];
            $result[$org]['finish'] += $item['finish_tasks'];
            $result[$org]['url'] =  Url::to([
                '/execute-tasks/data-department', 
                'idOrganization' => $item['org_code'],
                'period' => $period,
                'periodYear' => $periodYear,
            ]);
            $result[$org]['full_name'] = $item['org_code'] . ' ' . $item['org_name_full'];
        }
        return $result;
    }

    public static function getLeadersDepartment($data)
    {
        $deps = self::getDepartments($data);
        $result = [];
        foreach($deps as $dep=>$item) {
            if ($item['all'] > 0) {
                $persent = round($item['finish'] / $item['all'] * 100);
            }
            else {
                $persent = 0;
            }
            $result[$persent . '_' . $dep] = ['name' => $dep, 'per' => $persent];
        }
        krsort($result);        
        return $result;
    }

    public static function getLeadersOrganization($data)
    {
        $orgs = self::getOrganizations($data);
        $result = [];
        foreach($orgs as $org=>$item) {
            if ($item['all'] > 0) {
                $persent = round($item['finish'] / $item['all'] * 100);
            }
            else {
                $persent = 0;
            }
            $result[$persent . '_' . $org] = ['name' => $org, 'per' => $persent];
        }
        krsort($result);        
        return $result;
    }

    /**
     * @return array|null
     */
    public static function getDataByPeriod($period, $periodYear)
    {
        $query = (new Query())
            ->select("
                 t.*
                ,dep.id department_id
                ,dep.department_index
                ,dep.short_name department_name
                ,dep.department_name department_name_full
                ,org.short_name org_name
                ,org.name org_name_full
            ")
            ->from('{{%execute_tasks}} t')
            ->leftJoin('{{%department}} dep', 'dep.id = t.id_department')
            ->leftJoin('{{%organization}} org', 'org.code = t.org_code')
            ->where([
                't.period' => $period,
                't.period_year' => $periodYear,
            ])
            ->all(); 
        return $query;
    }

    


}
