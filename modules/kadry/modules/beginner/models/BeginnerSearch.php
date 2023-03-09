<?php

namespace app\modules\kadry\modules\beginner\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\kadry\modules\beginner\models\Beginner;

/**
 * BeginnerSearch represents the model behind the search form of `app\modules\kadry\modules\beginner\models\Beginner`.
 */
class BeginnerSearch extends Beginner
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_department', 'date_create', 'date_update'], 'integer'],
            [['fio', 'description', 'author'], 'safe'],
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
        $query = Beginner::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['date_employment' => SORT_DESC, 'fio' => SORT_ASC],
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
            'id_department' => $this->id_department,
            'date_create' => $this->date_create,
            'date_update' => $this->date_update,
        ]);

        $query->andFilterWhere(['like', 'fio', $this->fio])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
