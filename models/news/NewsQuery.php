<?php

namespace app\models\news;

/**
 * This is the ActiveQuery class for [[News]].
 *
 * @see News
 */
class NewsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    protected function base()
    {
        $query = parent::alias('t');
        return $query->select('t.*')
            ->distinct('true')
            ->leftJoin('{{%tree}} tree', 'tree.id=t.id_tree')
            ->andFilterWhere(['tree.module' => News::getModule()]);
    }


    /**
     * {@inheritdoc}
     * @return News[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return News|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
