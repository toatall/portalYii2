<?php

namespace app\models\news;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

/**
 * NewsSearch represents the model behind the search form of `app\models\news\News`.
 */
class NewsSearch extends News
{

    /**
     * Текстовое поле на форме поиска
     * @var string
     */
    public $searchText;

    /**
     * Дата от на форме поска
     * @var string
     */
    public $searchDate1;

    /**
     * Дата до на форме поиска
     * @var string
     */
    public $searchDate2;

    /**
     * Поиск по param1 в структуре
     * @var string
     */
    public $searchSection;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_tree', 'general_page', 'flag_enable', 'on_general_page', 'count_like', 'count_comment',
                'count_visit'], 'integer'],
            [['id_organization', 'title', 'message1', 'message2', 'author', 'date_start_pub', 'date_end_pub',
                'thumbail_title', 'thumbail_image', 'thumbail_text', 'date_create', 'date_edit', 'date_delete',
                'log_change', 'tags', 'date_sort'], 'safe'],
            [['searchText', 'searchDate1', 'searchDate2'], 'safe'],
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
    public function searchBackend($params, $idTree)
    {
        $query = News::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'date_sort' => SORT_DESC,
                    'id' => SORT_DESC,
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
            'id_tree' => $idTree,
            'general_page' => $this->general_page,
            'date_start_pub' => $this->date_start_pub,
            'date_end_pub' => $this->date_end_pub,
            'flag_enable' => $this->flag_enable,
            'date_create' => $this->date_create,
            'date_edit' => $this->date_edit,
            'on_general_page' => $this->on_general_page,
            'count_like' => $this->count_like,
            'count_comment' => $this->count_comment,
            'count_visit' => $this->count_visit,
            'date_sort' => $this->date_sort,
            'id_organization' => \Yii::$app->userInfo->current_organization,
        ]);

        if (!\Yii::$app->user->can('admin')) {
            $query->andFilterWhere(['date_delete' => null]);
        }

        $query->andFilterWhere(['like', 'id_organization', $this->id_organization])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'message1', $this->message1])
            ->andFilterWhere(['like', 'message2', $this->message2])
            ->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'tags', $this->tags]);

        return $dataProvider;
    }


    public function basePublicSearch()
    {
        $query = News::find()
            ->distinct(true)
            ->select('t.*')
            ->alias('t')
            ->leftJoin('{{%tree}} tree', 'tree.id = t.id_tree')
            ->where(['tree.module' => static::getModule()])
            ->orderBy('t.date_sort desc, t.id desc');

        $query->andWhere([
                't.date_delete' => null,
                't.flag_enable'=> 1,
            ])
            ->andFilterWhere(['<', 't.date_start_pub', (new Expression('getdate()'))])
            ->andFilterWhere(['>', 't.date_end_pub', (new Expression('getdate()'))]);

        if ($this->searchText) {
            $query->andWhere(['OR',
                ['like', 't.title', $this->searchText],
                ['like', 't.message1', $this->searchText],
                ['like', 't.message2', $this->searchText],
            ]);
        }

        if ($this->searchDate1) {
            $query->andFilterWhere(['>=', 't.date_create', $this->searchDate1]);
        }

        if ($this->searchDate2) {
            $query->andFilterWhere(['<=', 't.date_create', $this->searchDate2]);
        }

        if ($this->searchSection) {
            $query->andFilterWhere(['tree.param1' => $this->searchSection]);
        }

        $query->andFilterWhere(['t.tags' => $this->tags]);

        return $query;
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchPublic($params)
    {
        $this->load($params);
        $query = $this->basePublicSearch();

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['t.date_sort desc', 't.id desc'],
            ],
        ]);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['t.id_organization' => $this->id_organization]);
        $query->andFilterWhere(['t.id_tree' => $this->id_tree]);
        $query->andFilterWhere(['t.id' => $this->id]);

        return $dataProvider;
    }

    /**
     * Поиск новостей для УФНС
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchUfns($params)
    {
        $this->load($params);

        $query = $this->basePublicSearch();

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);



        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['t.id_organization' => '8600']);

        return $dataProvider;

    }

    /**
     * Поиск новостей для ИФНС
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchIfns($params)
    {
        $query = $this->basePublicSearch();

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

        $query->andFilterWhere(['<>', 't.id_organization', '8600']);

        return $dataProvider;

    }

}
