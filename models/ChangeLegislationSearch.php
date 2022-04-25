<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ChangeLegislation;

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
        $query = ChangeLegislation::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($this->searchText) {
            $query->andWhere(['or', 
                ['like', 'name', $this->searchText],
                ['like', 'text', $this->searchText],
            ]);
        }        

        if ($this->searchDate1) {
            $query->andWhere(['or', 
                ['>=', 'date_doc', $this->searchDate1],
                ['>=', 'date_doc_1', $this->searchDate1],
                ['>=', 'date_doc_2', $this->searchDate1],
                ['>=', 'date_doc_3', $this->searchDate1],
            ]);
        }

        if ($this->searchDate2) {
            $query->andWhere(['or', 
                ['<=', 'date_doc', $this->searchDate1],
                ['<=', 'date_doc_1', $this->searchDate1],
                ['<=', 'date_doc_2', $this->searchDate1],
                ['<=', 'date_doc_3', $this->searchDate1],
            ]);
        }

        $query->andWhere(['is_anti_crisis' => $isAntiCrisis]);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'date_doc' => $this->date_doc,
            'date_doc_1' => $this->date_doc_1,
            'date_doc_2' => $this->date_doc_2,
            'date_doc_3' => $this->date_doc_3,
            'date_create' => $this->date_create,
            'date_update' => $this->date_update,
        ]);

        $query->andFilterWhere(['like', 'type_doc', $this->type_doc])
            ->andFilterWhere(['like', 'number_doc', $this->number_doc])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'status_doc', $this->status_doc])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'log_change', $this->log_change]);

        return $dataProvider;
    }
}
