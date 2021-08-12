<?php

use app\modules\test\rules\TestStatisticRule;
use yii\db\Migration;

/**
 * Class m210804_062428_create_table_access_test_statistic
 */
class m210804_062428_create_table_access_test_statistic extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%access_test_statistic}}', [
            'id' => $this->primaryKey(),
            'id_test' => $this->integer()->notNull(),
            'id_user' => $this->integer()->notNull(),
            'date_create' => $this->dateTime(),            
        ]);

        // add permissions and rules
        $auth = Yii::$app->authManager;
        $permissionTestStatistic = $auth->createPermission('test-statistic');
        $rule = new TestStatisticRule();
        $auth->add($rule);
        $permissionTestStatistic->ruleName = $rule->name;
        $auth->add($permissionTestStatistic);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // remove permissions and rules
        $auth = Yii::$app->authManager;
        $permission = $auth->getPermission('test-statistic');
        if ($permission) {
            //$auth->re;
            $auth->remove($permission);
        }        
        $rule = $auth->getRule('rule-test-statistic');
        if ($rule) {
             $auth->remove($rule);
        }

        $this->dropTable('{{%access_test_statistic}}');
    }

}
