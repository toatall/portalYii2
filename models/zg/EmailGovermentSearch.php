<?php

namespace app\models\zg;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\zg\EmailGoverment;

/**
 * EmailGovermentSearch represents the model behind the search form of `app\models\zg\EmailGoverment`.
 */
class EmailGovermentSearch extends EmailGoverment
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['org_name', 'ruk_name', 'telephone', 'email', 'post_address', 'date_create', 'date_edit', 'author'], 'safe'],
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
        $query = EmailGoverment::find();

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
            'date_create' => $this->date_create,
            'date_edit' => $this->date_edit,
        ]);

        $query->andFilterWhere(['like', 'org_name', $this->org_name])
            ->andFilterWhere(['like', 'ruk_name', $this->ruk_name])
            ->andFilterWhere(['like', 'telephone', $this->telephone])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'post_address', $this->post_address])
            ->andFilterWhere(['like', 'author', $this->author]);

        return $dataProvider;
    }
}
