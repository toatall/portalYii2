<?php
namespace app\models\rules;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\rbac\Rule;

class NewsRule extends Rule
{
    /**
     * @var string
     */
    public $name = 'isNews';

    /**
     * {@inheritDoc}
     * @see \yii\rbac\Rule::execute()
     * @param $params \common\models\News
     */
    public function execute($user, $item, $params)
    {
        if (\Yii::$app->user->isGuest)
        {
            return false;
        }
        return true;
    }
}
