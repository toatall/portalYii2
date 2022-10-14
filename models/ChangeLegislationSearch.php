<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ChangeLegislation;
use yii\db\Expression;

/**
 * ChangeLegislationSearch represents the model behind the search form of `app\models\ChangeLegislation`.
 */
class ChangeLegislationSearch extends ChangeLegislation
{

    public $searchDate1;
    public $searchDate2;
    public $searchText;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['type_doc', 'date_doc', 'number_doc', 'name', 'date_doc_1', 'date_doc_2', 'date_doc_3', 'status_doc', 
                'text', 'date_create', 'date_update', 'author', 'log_change'], 'safe'],
            [['searchDate1', 'searchDate2', 'searchText'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $isAntiCrisis=false)
    {
        $query = ChangeLegislation::find()
            ->alias('t');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'date_doc' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);

        if (!empty(trim($this->searchText))) {
            $search = stripslashes($this->searchText);        
            $query->innerJoin("FREETEXTTABLE({{%change_legislation}}, [[text]], '{$search}') f", 't.id = f.[[KEY]]');
            $expression = new Expression("FREETEXT([[text]], '{$search}')");
            $query->orderBy(['f.[[RANK]]' => SORT_DESC]);
        }
        else {
            $expression = new Expression('');
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($this->searchText) {
            $query->andWhere(['or', 
                ['like', 't.name', $this->searchText],
                //['like', 'text', $this->searchText],
                $expression,
            ]);
        }        

        if ($this->searchDate1) {
            $query->andWhere(['or', 
                ['>=', 't.date_doc', $this->searchDate1],
                ['>=', 't.date_doc_1', $this->searchDate1],
                ['>=', 't.date_doc_2', $this->searchDate1],
                ['>=', 't.date_doc_3', $this->searchDate1],
            ]);
        }

        if ($this->searchDate2) {
            $query->andWhere(['or', 
                ['<=', 't.date_doc', $this->searchDate1],
                ['<=', 't.date_doc_1', $this->searchDate1],
                ['<=', 't.date_doc_2', $this->searchDate1],
                ['<=', 't.date_doc_3', $this->searchDate1],
            ]);
        }

        $query->andWhere(['t.is_anti_crisis' => $isAntiCrisis]);

        return $dataProvider;
    }
}
