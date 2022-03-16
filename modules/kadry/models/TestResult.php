<?php

namespace app\modules\kadry\models;

use app\behaviors\AuthorBehavior;
use app\behaviors\ChangeLogBehavior;
use app\behaviors\DatetimeBehavior;
use app\models\Organization;
use app\models\User;
use Yii;

/**
 * This is the model class for table "{{%kadry_test_result}}".
 *
 * @property int $id
 * @property string $org_code
 * @property string $period
 * @property int $period_year
 * @property int $count_mark_five
 * @property int $count_mark_four
 * @property int $count_mark_three
 * @property int $count_kpk
 * @property float $avg_mark
 * @property string $date_create
 * @property string $date_update
 * @property string|null $log_change
 * @property string $author
 *
 * @property Organization $orgCode
 * @property User $author0
 */
class TestResult extends \yii\db\ActiveRecord
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

    public static function periodNameByCode($code)
    {
        return isset(self::$periods[$code]) ? 
            self::$periods[$code] : 
            null;
    }

    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%kadry_test_result}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['org_code', 'period', 'period_year', 'count_mark_five', 'count_mark_four', 'count_mark_three', 'count_kpk', 'avg_mark'], 'required'],
            [['period_year', 'count_mark_five', 'count_mark_four', 'count_mark_three', 'count_kpk'], 'integer'],
            [['avg_mark'], 'number'],
            [['date_create', 'date_update'], 'safe'],
            [['log_change'], 'string'],
            [['org_code'], 'string', 'max' => 5],
            [['period'], 'string', 'max' => 30],
            [['author'], 'string', 'max' => 250],
            [['org_code'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::class, 'targetAttribute' => ['org_code' => 'code']],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author' => 'username']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'org_code' => 'Org Code',
            'period' => 'Period',
            'period_year' => 'Period Year',
            'count_mark_five' => 'Count Mark Five',
            'count_mark_four' => 'Count Mark Four',
            'count_mark_three' => 'Count Mark Three',
            'count_kpk' => 'Count Kpk',
            'avg_mark' => 'Avg Mark',
            'date_create' => 'Date Create',
            'date_update' => 'Date Update',
            'log_change' => 'Log Change',
            'author' => 'Author',
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
}
