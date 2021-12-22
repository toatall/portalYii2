<?php

namespace app\models\lifehack;

use yii\data\ActiveDataProvider;

/**
 * Поиск по лайфхакам
 * @property int $id
 * @property string $org_code
 * @property string $tags
 * @property string $title
 * @property string|null $text
 * @property string|null $author_name
 * @property string|null $date_create
 * @property string|null $date_update
 * @property string|null $usernmae
 * @property string|null $log_change
 */
class LifehackSearch extends Lifehack
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['org_code', 'title', 'text', 'author_name'], 'safe'],
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
            'org_code' => $this->org_code,            
        ]);

        $query->andFilterWhere(['like', 'tags', $this->tags]);
        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['like', 'text', $this->text]);
        $query->andFilterWhere(['like', 'author_name', $this->author_name]);

        return $dataProvider;
    }
    
}
