<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MigrantsQuestionnation;

/**
 * MigrantsQuestionnationSearch represents the model behind the search form of `app\models\MigrantsQuestionnation`.
 */
class MigrantsQuestionnationSearch extends MigrantsQuestionnation
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'date_create', 'date_update'], 'integer'],
            [['ul_name', 'ul_inn', 'ul_kpp', 'date_send_notice', 'region_migrate', 'cause_migrate', 'author'], 'safe'],
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
    public function search($params)
    {
        $query = MigrantsQuestionnation::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['date_send_notice' => SORT_DESC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'date_send_notice' => $this->date_send_notice,
            'date_create' => $this->date_create,
            'date_update' => $this->date_update,
        ]);

        $query->andFilterWhere(['like', 'ul_name', $this->ul_name])
            ->andFilterWhere(['like', 'ul_inn', $this->ul_inn])
            ->andFilterWhere(['like', 'ul_kpp', $this->ul_kpp])
            ->andFilterWhere(['like', 'region_migrate', $this->region_migrate])
            ->andFilterWhere(['like', 'cause_migrate', $this->cause_migrate])
            ->andFilterWhere(['like', 'author', $this->author]);

        return $dataProvider;
    }
}
