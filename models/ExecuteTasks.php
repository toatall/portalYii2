<?php

namespace app\models;

use app\behaviors\AuthorBehavior;
use app\behaviors\ChangeLogBehavior;
use app\behaviors\DatetimeBehavior;
use app\models\department\Department;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

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
        '03_2_kv' => '1 квартал',
        '06_2_kv' => '2 квартал',
        '09_2_kv' => '3 квартал',
        '12_2_kv' => '4 квартал',
        // полугодия
        '06_3_pol' => '1 полугодие',
        '12_3_pol' => '2 полугодие',
        // 9 месяцев
        '09_4_9mes' => '9 месяцев',
        // год
        '12_5_god' => 'Годовой',
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
        for ($i=$currentY-2; $i<=$currentY+2; $i++) {
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


}
