<?php

namespace app\modules\log\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\log\models\Log;

/**
 * LogSearch represents the model behind the search form of `app\modules\log\models\Log`.
 */
class LogSearch extends Log
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'level'], 'integer'],
            [['category', 'url', 'statusCode', 'statusText', 'user', 'prefix', 'message'], 'safe'],
            [['log_time'], 'number'],
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
        $query = Log::find()->with('userModel');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {            
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'level' => $this->level,
            'log_time' => $this->log_time,
        ]);

        $query->andFilterWhere(['ilike', 'category', $this->category])
            ->andFilterWhere(['ilike', 'url', $this->url])
            ->andFilterWhere(['ilike', 'statusCode', $this->statusCode])
            ->andFilterWhere(['ilike', 'statusText', $this->statusText])
            ->andFilterWhere(['ilike', 'user', $this->user])
            ->andFilterWhere(['ilike', 'prefix', $this->prefix])
            ->andFilterWhere(['ilike', 'message', $this->message]);

        return $dataProvider;
    }
}
