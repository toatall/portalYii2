<?php
namespace app\modules\test\rules;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Yii;
use yii\db\Query;
use yii\rbac\Item;
use yii\rbac\Rule;

class TestStatisticRule extends Rule
{
    /**
     * @var string
     */
    public $name = 'rule-test-statistic';

    /**
     * {@inheritDoc}
     * @see \yii\rbac\Rule::execute()
     * @param int $user
     * @param Item $item
     * @param array $params 
     */
    public function execute($user, $item, $params)
    {   
        if (Yii::$app->user->isGuest) {
            return false;
        }
       
        return $this->findUserAccessByTestId($user, isset($params['test']['id']) ? $params['test']['id'] : 0);
    }

    /**
     * @param int $user
     * @param int $id
     */
    protected function findUserAccessByTestId($user, $id)
    {
        return (new Query())
            ->from('{{%access_test_statistic}} t')
            ->where([
                't.id_user' => $user,                
            ])
            ->andWhere(['or', ['t.id_test' => $id], ['t.id_test' => 0]])
            ->exists();
    }

}
