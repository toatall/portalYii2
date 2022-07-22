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
     * Поиск по организации (автору)
     * @var string
     */
    public $searchOrgName;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['org_code', 'title', 'text', 'author_name', 'searchOrgName'], 'safe'],
        ];
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = static::find()->alias('t')
            ->select('t.*')
            ->leftJoin('{{%organization}} org', 't.org_code=org.code');

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

        $dataProvider->setSort([
            'attributes' => [
                'searchOrgName' => [
                    'asc' => ['org.name' => SORT_ASC],
                    'desc' => ['org.name' => SORT_DESC],
                    'default' => SORT_ASC,
                ],
                'title',
            ],
        ]);

        // grid filtering conditions
        $query->andFilterWhere([
            't.id' => $this->id,            
            't.org_code' => $this->org_code,
        ]);

        $query->andFilterWhere(['like', 't.tags', $this->tags]);
        $query->andFilterWhere(['like', 't.title', $this->title]);
        $query->andFilterWhere(['like', 't.text', $this->text]);
        $query->andFilterWhere(['like', 't.author_name', $this->author_name]);
        if ($this->searchOrgName) {
            $query->andFilterWhere(['or', 
                ['like', 't.author_name', $this->searchOrgName],
                ['like', 'org.name', $this->searchOrgName],
            ]);  
        }

        return $dataProvider;
    }
    
}
