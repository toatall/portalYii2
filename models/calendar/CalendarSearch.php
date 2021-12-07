<?php

namespace app\models\calendar;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CalendarSearch represents the model behind the search form of `app\models\Calendar`.
 */
class CalendarSearch extends Calendar
{

    /**
     * @var string
     */
    public $searchMonth;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['date', 'color', 'code_org', 'date_create', 'date_delete', 'author', 'log_change', 'is_global', 'searchMonth'], 'safe'],
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
        $query = Calendar::find();

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

        if ($this->searchMonth) {
            $query->andWhere('MONTH(date) = MONTH(:date1) AND YEAR(date) = YEAR(:date2)', [
                ':date1' => $this->searchMonth,
                ':date2' => $this->searchMonth,
            ]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'date' => $this->date,
            'date_create' => $this->date_create,
            'date_delete' => $this->date_delete,            
        ]);

        // админ видит записи всех организаций
        if (\Yii::$app->user->can('admin')) {
            $query->andFilterWhere(['like', 'code_org', $this->code_org]);
        }
        // остальные пользователи видят только записи по своей организации
        else {
            $query->andWhere(['code_org' => \Yii::$app->user->identity->current_organization]);
        }
        
        $query->andFilterWhere(['like', 'color', $this->color])
            ->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'log_change', $this->log_change]);

        return $dataProvider;
    }
}
