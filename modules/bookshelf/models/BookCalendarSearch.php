<?php

namespace app\modules\bookshelf\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\bookshelf\models\BookShelfCalendar;

/**
 * BookCalendarSearch represents the model behind the search form of `app\modules\bookshelf\models\BookShelfCalendar`.
 */
class BookCalendarSearch extends BookShelfCalendar
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['date_birthday', 'date_die', 'writer', 'photo', 'description', 'author', 'date_create', 'date_update', 'log_change'], 'safe'],
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
        $query = BookShelfCalendar::find();

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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'date_birthday' => $this->date_birthday,
            'date_die' => $this->date_die,
            'date_create' => $this->date_create,
            'date_update' => $this->date_update,
        ]);

        // $query->andFilterWhere(['like', 'writer', $this->writer])
        //     ->andFilterWhere(['like', 'photo', $this->photo])
        //     ->andFilterWhere(['like', 'description', $this->description])
        //     ->andFilterWhere(['like', 'author', $this->author])
        //     ->andFilterWhere(['like', 'log_change', $this->log_change]);

        return $dataProvider;
    }
}
