<?php


namespace app\models\regecr;


use yii\data\ActiveDataProvider;

class RegEcrSearch extends RegEcr
{
    /**
     * @var string
     */
    public $searchDate1;

    /**
     * @var string
     */
    public $searchDate2;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['searchDate1', 'searchDate2', 'code_org'], 'safe'],
        ];
    }

    /**
     * {@inheritDoc}
     * @return array
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'searchDate1' => 'Дата с',
            'searchDate2' => 'Дата по',
        ]);
    }

    /**
     * @param $params
     * @return \yii\db\ActiveQuery
     */
    public function search($params)
    {
        $query = RegEcr::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($this->searchDate1) {
            $query->andFilterWhere(['>=', 'date_reg', $this->searchDate1]);
        }
        if ($this->searchDate2) {
            $query->andFilterWhere(['<=', 'date_reg', $this->searchDate2]);
        }

        $query->andFilterWhere(['code_org' => $this->code_org]);
        $query->distinct(true);
        return $dataProvider;
    }
}