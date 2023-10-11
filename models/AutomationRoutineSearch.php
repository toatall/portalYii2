<?php

namespace app\models;

use yii\data\ActiveDataProvider;
use app\models\AutomationRoutine;

/**
 * AutomationRoutineSearch represents the model behind the search form of `app\models\AutomationRoutine`.
 */
class AutomationRoutineSearch extends AutomationRoutine
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['title', 'description', 'ftp_path', 'author', 'date_create', 'date_update', 'owners'], 'safe'],
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
        $query = AutomationRoutine::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'date_create' => SORT_DESC,
                ],
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
            'date_create' => $this->date_create,
            'date_update' => $this->date_update,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'ftp_path', $this->ftp_path])
            ->andFilterWhere(['like', 'owners', $this->owners]);
            // ->andFilterWhere(['like', 'author', $this->author]);

        return $dataProvider;
    }
}
