<?php


namespace app\models\conference;


use yii\data\ActiveDataProvider;

class VksKonturTalkSearch extends VksKonturTalk
{
    
    public function rules()
    {
        return [
            [['code_org', 'duration', 'date_start', 'theme', 'place'], 'safe'],
            [['id'], 'integer'],
        ];
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = static::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'date_start' => SORT_DESC,
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
            'type_conference' => static::getType(),
            'duration' => $this->duration,
        ]);

        if ($this->date_start) {
            $query->andWhere('cast([[date_start]] as date) = cast(:d1 as date)', [
                ':d1' => \Yii::$app->formatter->asDatetime($this->date_start),
            ]);
        }
        $query->andFilterWhere(['like', 'theme', $this->theme]); 
        $query->andFilterWhere(['like', 'code_org', $this->code_org]); 
        $query->andFilterWhere(['like', 'place', $this->place]);

        return $dataProvider;
    }

}