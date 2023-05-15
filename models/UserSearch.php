<?php
namespace app\models;

use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * Поиск пользователей
 * 
 * @author totall
 */
class UserSearch extends User
{

    /**
     * Идентификаторы пользователей 
     * Пользователи будут исключены из рузультата поиска
     * @var int[]
     */
    public $excludeIdUser;

    /**
     * Идентификаторы групп
     * Пользователи входящие в эти группы будут исключены
     * @var int[]|int
     */
    public $excludeIdGroup;

    /**
     * Идентификаторы групп
     * В результат попадут только пользователи, входящие в эти группы
     * @var int[]|int
     */
    public $includeIdGroup;


    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            [['excludeIdUser', 'excludeIdGroup', 'includeIdGroup', 'user_disabled_ad'], 'safe'],
            [['department', 'username', 'username_windows', 'fio', 'default_organization'], 'string'],
            [['user_disabled_ad'], 'boolean'],          
        ];
    }


    // @todo delete all params after $params
    /**
     * @param array $params
     * @return \yii\data\ActiveDataProvider
     */
    public function search($params, $excludeId = null, $excludeIdGroup=null, $excludeRole=null)
    {                
        $query = self::find()->alias('t');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->user_disabled_ad = 0; // default     
        $this->load($params); 
        
        $this->filterExclude($query);
        $this->filterInclude($query);

        $query->andFilterWhere(['like', 't.department', $this->department]);
        $query->andFilterWhere(['like', 't.username', $this->username]);
        $query->andFilterWhere(['like', 't.username_windows', $this->username_windows]);
        $query->andFilterWhere(['like', 't.fio', $this->fio]);
        $query->andFilterWhere(['like', 't.default_organization', $this->default_organization]);

        $query->andFilterWhere(['user_disabled_ad' => $this->user_disabled_ad]);
        
        return $dataProvider;
    }

    /**
     * Фильтр исключения
     * 
     * @param \yii\db\ActiveQuery $query
     * @return \yii\db\ActiveQuery
     */
    protected function filterExclude(\yii\db\ActiveQuery $query): \yii\db\ActiveQuery
    {
        // исключение id пользователей
        if (!empty($this->excludeIdUser)) {
            $query->andWhere(['not in', 't.id', (array)$this->excludeIdUser]);
        }
        // исключение id групп       
        if (!empty($this->excludeIdGroup)) {
            $query->withQuery(
                (new Query())
                    ->from('{{%grant_access_group__user}}')
                    ->where(['in', 'id_group', (array)$this->excludeIdGroup])
                , 'grExcl'
            )
            ->leftJoin('grExcl', 'grExcl.id_user = t.id')
            ->andWhere(['grExcl.id_user' => null]);
        }
        return $query;
    }

    /**
     * Фильтр для включения 
     * 
     * @param \yii\db\ActiveQuery $query
     * @return \yii\db\ActiveQuery
     */
    protected function filterInclude(\yii\db\ActiveQuery $query): \yii\db\ActiveQuery
    {
        // только пользователи состоящ в группе       
        if (!empty($this->includeIdGroup)) {
            $query->withQuery(
                (new Query())
                    ->from('{{%grant_access_group__user}}')
                    ->where(['in', 'id_group', (array)$this->includeIdGroup])
                , 'grIncl'
            )
            ->rightJoin('grIncl', 'grIncl.id_user = t.id');
        }

        return $query;
    }

}