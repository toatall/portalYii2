<?php

namespace app\models;

use app\behaviors\AuthorBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Query;

/**
 * This is the model class for table "{{%declare_campaign_usn}}".
 *
 * @property int $id
 * @property string $year
 * @property string $date
 * @property string $deadline
 * @property string $org_code
 * @property int $count_np
 * @property int $count_np_provides_reliabe_declare
 * @property int $count_np_provides_not_required
 * @property float $accrued_sum
 * @property int|null $date_create
 * @property string|null $author
 * 
 * @property string $previous_count_np_provides_reliabe_declare
 * @property string $previous_count_np_provides_not_required
 * @property string $previous_date
 * 
 * 
 */
class DeclareCampaignUsn extends \yii\db\ActiveRecord
{

    /**
     * Поле для массового ввода данных
     * 
     * Формат воода
     * {{org_code}}    {{count_np}}    {{count_np_provides_reliabe_declare}}    {{count_np_provides_not_required}}    {{accrued_sum}}
     * 
     * (разделитель между столбцов - tab, между строк - enter)
     * 
     * @var string
     */
    public $bulkData;

    private $_previousData;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%declare_campaign_usn}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [            
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'date_create',
                'updatedAtAttribute' => null,
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
            [['year', 'date', 'deadline', 'org_code', 'count_np', 
                'count_np_provides_reliabe_declare', 'count_np_provides_not_required'], 'required'],
            [['count_np', 'count_np_provides_reliabe_declare', 'count_np_provides_not_required', 'date_create'], 'integer'],
            [['accrued_sum'], 'double'],
            [['year'], 'string', 'max' => 4],
            [['date'], 'string', 'max' => 10],
            [['org_code'], 'string', 'max' => 5],
            [['author'], 'string', 'max' => 250],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author' => 'username']],
            [['bulkData'], 'string'],
        ];
    }

    /**
     * Удаление кэшированных данных
     */
    private function deleteCache()
    {
        Yii::$app->cache->delete('declare_campaing_usn_last_data');
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->deleteCache();
    }

    /**
     * {@inheritdoc}
     */
    public function afterDelete()
    {
        $this->deleteCache();
    }

    /**
     * Наименование роли модератора
     * @return string
     */
    public static function getRoleModerator()
    {
        return Yii::$app->params['declare-campaign-usn']['role-moderator'];
    }

    /**
     * Прверка прав на модерацию
     * @return bool
     */
    public static function isRoleModerator()
    {
        return Yii::$app->user->can('admin') || Yii::$app->user->can(self::getRoleModerator());
    }

    /**
     * Получение данных за последнюю дату
     * @return array
     */
    public static function findWithLastDate()
    {
        return Yii::$app->cache->getOrSet('declare_campaing_usn_last_data', function() {
            $res = [];
            
            $deadlines = (new Query())
                ->from(self::tableName())
                ->select('deadline')
                ->distinct(true)
                ->orderBy(['deadline' => SORT_DESC])
                ->indexBy('deadline')
                ->all();

            if (!$deadlines) {
                return [];
            }

            foreach($deadlines as $deadline) {
                $res[$deadline['deadline']] = self::find()
                    ->select('MAX(year) AS [year], MAX(date) AS [date], deadline AS [deadline] '
                        . ', [[org_code]], [[count_np]], [[count_np_provides_reliabe_declare]], [[count_np_provides_not_required]], [[accrued_sum]]')
                    ->where(['deadline' => $deadline])
                    ->orderBy("case when [[org_code]] = '8600' then 1 else 0 end ASC, [[org_code]] ASC")
                    ->indexBy('org_code')
                    ->groupBy('[[deadline]], [[org_code]], [[count_np]], [[count_np_provides_reliabe_declare]], [[count_np_provides_not_required]], [[accrued_sum]]')
                    ->all();
            }           

            return $res;
        }, 0);
    }

    /**
     * Поиск данных по текущей организации за прошлый период
     * @return array|false
     */
    private function getPreviousData() 
    {        
        // поиск предыдущей даты
        $queryDate = (new Query())
            ->select('date')
            ->from(DeclareCampaignUsn::tableName())
            ->where('{{date}} < cast(:d as date)', [':d' => $this->date])
            ->andWhere(['org_code' => $this->org_code])
            ->andWhere('deadline = CAST(:deadline AS DATE)', [':deadline' => $this->deadline])
            ->orderBy(['date' => SORT_DESC])
            ->one();
        if ($queryDate == null) {
            return false;
        }

        return self::find()            
            ->where('date = cast(:d as date)', [':d' => $queryDate['date']])
            ->andWhere(['org_code' => $this->org_code])                      
            ->one();
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'year' => 'Год',
            'date' => 'Дата',
            'org_code' => 'Код НО',
            'count_np' => 'Количество НП',            
            'count_np_provides_reliabe_declare' => 'Количество НП представивших верные Уведомления',
            'count_np_provides_not_required' => 'Количество НП, которым Уведомление представлять не требуется',
            'deadline' => 'Срок уплаты',
            'accrued_sum' => 'Сумма начисленного налога по Уведомлениям',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->_previousData = $this->getPreviousData();
    }

    /**
     * Количество НП представивших верные Уведомления за прошлый период
     * @return int|false
     */
    public function getPrevious_count_np_provides_reliabe_declare()
    {
        return $this->_previousData['count_np_provides_reliabe_declare'] ?? false;
    }

    /**
     * Количество НП, которым Уведомление представлять не требуется за прошлый период
     * @return int|false
     */
    public function getPrevious_count_np_provides_not_required()
    {
        return $this->_previousData['count_np_provides_not_required'] ?? false;
    }

    /**
     * Дата за прошлый период
     * @return int|false
     */
    public function getPrevious_date()
    {
        return isset($this->_previousData['date']) ? Yii::$app->formatter->asDate($this->_previousData['date']) : false;
    }

    /**
     * Отчетные года
     * @return array
     */
    public static function getReportsYears()
    {
        $year = date('Y');
        return [
            $year - 1 => $year - 1,
            $year => $year,
            $year + 1 => $year + 1,
        ];
    }

    /**
     * Сроки уплаты
     * @return array
     */
    public static function getReportsDeadline()
    {        
        return [
            '28.04' => '28.04',
            '28.07' => '28.07',
            '30.10' => '30.10',
        ];
    }
    
    /**
     * Получение (поиск или создание) по организациям, 
     * отчетному году и отчетной дате
     * @param string $year
     * @param string $date
     * @param string $deadline
     * @return array
     */
    public static function getModels($year, $date, $deadline)
    {
        $orgs = (new Query())
            ->from('{{%organization}}')
            ->where(['date_end' => null])
            ->andWhere(['not', ['code' => ['8625']]])
            ->andWhere(['like', 'code', '86__', false])
            ->orderBy(['code' => SORT_ASC])
            ->all();
        $models = [];
        foreach($orgs as $org) {
            $model = self::find()->where([
                'year' => $year,
                'date' => $date,
                'org_code' => $org['code'],
                'deadline' => $deadline . '.' . $year,
            ])->one();
            if ($model === null) {
                $model = new self([
                    'year' => $year,
                    'date' => $date,
                    'org_code' => $org['code'],
                    'deadline' => $deadline . '.' . $year,
                ]);
            }
            $models[$org['code']] = $model;
        }
        return $models;
    }   

    /**
     * Сохранение данных из архива моделей
     * @param DeclareCampaignUsn[] $models
     * @return bool
     */
    public static function saveModels($models) 
    {
        $result = true;
        foreach($models as $model) {
            if (!$model->save()) {
                $result = false;
            }
        }
        return $result;
    }

    /**
     * Сохранение данных из архива моделей
     * @param DeclareCampaignUsn[] $models
     * @return bool
     */
    public static function deleteModels($models)
    {
        $result = true;
        foreach($models as $model) {
            if (!$model->delete()) {
                $result = false;
            }
        }
        return $result;
    }    

}
