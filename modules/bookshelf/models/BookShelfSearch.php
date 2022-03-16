<?php

namespace app\modules\bookshelf\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\bookshelf\models\BookShelf;
use yii\db\Expression;

/**
 * BookShelfSearch represents the model behind the search form of `app\modules\bookshelf\models\BookShelf`.
 */
class BookShelfSearch extends BookShelf
{

    /**
     * Поиск по названию, автору, описанию
     * @var string
     */
    public $searchText;

    /**
     * Показывать только новые поступления
     * @var boolean
     */
    public $searchIsNew;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'book_status'], 'integer'],
            [['writer', 'title', 'place', 'photo', 'description', 'date_received', 'date_away', 'author', 'date_create', 'date_update', 'log_change', 'searchText'], 'safe'],
            [['rating'], 'number'],
            [['searchIsNew'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return parent::attributeLabels() + [
            'searchText' => 'Поиск (название, автор, описание)',
            'searchIsNew' => 'Новые поступления',
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
        $query = BookShelf::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'title' => SORT_ASC,                    
                    'writer' => SORT_ASC,
                    'date_received' => SORT_DESC,
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
            'rating' => $this->rating,
            'date_received' => $this->date_received,
            'date_away' => $this->date_away,
            'date_create' => $this->date_create,
            'date_update' => $this->date_update,
        ]);

        if (!empty($this->searchText)) {
            $query->andWhere(['or', 
                ['like', 'writer', $this->searchText],
                ['like', 'title', $this->searchText],
                ['like', 'description', $this->searchText],
            ]);
        }

        if ($this->searchIsNew) {
            $query->andWhere(['>=', 'date_received', new Expression("dateadd(month, -1, getdate())")]);
        }

        if (self::isEditor()) {
            $query->andFilterWhere(['book_status' => $this->book_status]);
        }        
        else {
            $query->andWhere(['book_status' => self::STATUS_IN_STOCK]);
        }

        // $query->andFilterWhere(['like', 'writer', $this->writer])
        //     ->andFilterWhere(['like', 'title', $this->title])
        //     ->andFilterWhere(['like', 'place', $this->place])
        //     ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
