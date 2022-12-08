<?php


namespace app\models\conference;


use yii\data\ActiveDataProvider;

class VksKonturTalkSearch extends VksKonturTalk
{
    
    public function rules()
    {
        return [
            [['code_org', 'duration', 'date_start', 'theme'], 'safe'],
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

        $query->andFilterWhere(['like', 'date_start', $this->date_start]);
        $query->andFilterWhere(['like', 'theme', $this->theme]); 
        $query->andFilterWhere(['like', 'code_org', $this->code_org]); 

        return $dataProvider;
    }

}