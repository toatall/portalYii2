<?php

namespace app\modules\kadry\models;

use app\behaviors\DatetimeBehavior;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * Award (награды и поощрения сотрудников округа)
 * 
 * @property int $id
 * @property string $org_code
 * @property string $org_name
 * @property string $fio
 * @property string $dep_index
 * @property string $dep_name
 * @property string $post
 * @property string $aw_name
 * @property string $aw_doc
 * @property string $aw_doc_num
 * @property string $aw_date_doc
 * @property string $date_create
 * 
 * @property string $flag_dks
 * 
 */
class Award extends ActiveRecord
{

    /**
     * Даты для поиска в диапазоне
     */
    public $aw_date_doc1;
    public $aw_date_doc2;

    /**
     * @var string
     */
    public $searchMonth;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%awards}}';
    }

    /**
     * {@inheritdoc}
     * Переопределение базы данных
     * @return \yii\db\Connection
     */
    public static function getDb()
    {
        return \Yii::$app->dbDKS;
    }    

    /**
     * Роль для просмотра
     * @return string
     */
    public static function roleReader()
    {
        return Yii::$app->params['modules']['kadry']['award']['roles']['reader'];
    }

    /**
     * Роль модератора
     * @return string
     */
    public static function roleModerator()
    {
        return Yii::$app->params['modules']['kadry']['award']['roles']['moderator'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'org_code' => 'Код НО',
            'org_name' => 'Наименование НО',
            'fio' => 'ФИО',
            'dep_index' => 'Номер отдела',
            'dep_name' => 'Наименование отдела',
            'post' => 'Должность',
            'aw_name' => 'Награда',
            'aw_doc' => 'Документ', 
            'aw_doc_num' => 'Номер документа', 
            'aw_date_doc' => 'Дата документа', 
            'date_create' => 'Дата создания',
            'date_update' => 'Дата изменения',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [            
            [
                'class' => DatetimeBehavior::class,
                'createdAtAttribute' => 'date_create',
                'updatedAtAttribute' => 'date_update',
            ],          
        ];
    }    

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer', 'on' => 'crate-update'],
            [['fio', 'aw_name', 'org_name', 'org_code'], 'required', 'on' => 'create-update'],
            [['org_code', 'org_name', 'fio', 'dep_index', 'dep_name', 'post', 
                'aw_name', 'aw_doc', 'aw_doc_num', 'aw_date_doc', 'date_create', 
                'aw_date_doc1', 'aw_date_doc2'], 'safe', 'on' => 'create-update'],         
            [['org_code', 'fio', 'dep_name', 'post', 'aw_name', 'aw_doc', 'aw_doc_num', 'aw_date_doc'], 'safe'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        
        $query = self::find();        
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'org_code' => SORT_ASC,
                    'org_name' => SORT_ASC,
                    'fio' => SORT_ASC, 
                    'dep_name' => SORT_ASC, 
                    'post' => SORT_ASC,
                ],
            ],
        ]);

        $this->load($params);
       
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        
        if (!$this->org_code) {            
            $this->org_code = Yii::$app->user->identity->current_organization;
        }
      
        $query->andFilterWhere([
            'dep_index' => $this->dep_index,      
            'org_code' => $this->org_code,
            //'aw_date_doc' => $this->aw_date_doc,
        ]);
       

        if ($this->aw_date_doc) {
            $dates = explode('/', $this->aw_date_doc);
            if (count($dates) == 1) {
                $query->andWhere(['aw_date_doc' => $dates]);
            }   
            else {         
                if (isset($dates[0])) {
                    $query->andWhere(['>=', 'aw_date_doc', $dates[0]]);
                }
                if (isset($dates[1])) {
                    $query->andWhere(['<=', 'aw_date_doc', $dates[1]]);
                }
            }
        }

        $query->andFilterWhere(['like', 'fio', $this->fio])
            ->andFilterWhere(['like', 'org_name', $this->org_name])
            ->andFilterWhere(['like', 'dep_name', $this->dep_name])
            ->andFilterWhere(['like', 'post', $this->post])
            ->andFilterWhere(['like', 'aw_name', $this->aw_name])
            ->andFilterWhere(['like', 'aw_doc', $this->aw_doc])
            ->andFilterWhere(['like', 'aw_doc_num', $this->aw_doc_num]);

        // if ($this->aw_date_doc1) {
        //     $query->andWhere(['>=', 'aw_doc_num', $this->aw_date_doc1]);
        // }
        // if ($this->aw_date_doc2) {
        //     $query->andWhere(['<=', 'aw_doc_num', $this->aw_date_doc2]);
        // }

        return $dataProvider;
    }

    /**
     * Уникальный список организаций (на основании данных)
     * @return array
     */
    public function getOrganizations()
    {
        return (new Query())
            ->from(self::tableName())
            ->groupBy('org_code, org_name')
            ->select('org_code, org_name')      
            ->orderBy(['org_name' => SORT_ASC])
            ->all(self::getDb());
    }

    /**
     * Поиск имени организации по ее коду
     * @return string|null
     */
    private function getOrgName()
    {
        return (new Query())
            ->from(self::tableName())
            ->select('org_name')
            ->where(['org_code' => $this->org_code])
            ->one(self::getDb())['org_name'] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeValidate()
    {
        if (!parent::beforeValidate()) {
            return false;
        }
        $this->org_name = $this->getOrgName();
        return true;
    }

}
