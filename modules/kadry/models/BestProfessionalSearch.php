<?php

namespace app\modules\kadry\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * 
 */
class BestProfessionalSearch extends BestProfessional
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [           
            [['org_code', 'department'], 'safe'],
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
        $query = BestProfessional::find();
        $query->orderBy([
            'period_year' => SORT_DESC,
            'period' => SORT_DESC,
        ]);

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
            'org_code' => $this->org_code,
            'department' => $this->department,
        ]);

        return $dataProvider;
    }


    public function dropDownOrganizations()
    {
        $query = (new Query())
            ->select('t.org_code, org.name')
            ->from('{{%best_professional}} t')
            ->leftJoin('{{%organization}} org', 't.org_code=org.code')
            ->groupBy('t.org_code, org.name')
            ->orderBy('org.name')
            ->all();
        return ArrayHelper::map($query, 'org_code', 'name');
    }

    public function dropDownDepartment()
    {
        $query = (new Query())
            ->select('department')
            ->from('{{%best_professional}}')
            ->groupBy('department')
            ->orderBy('department')
            ->all();
        return ArrayHelper::map($query, 'department', 'department');
    }
    

}
