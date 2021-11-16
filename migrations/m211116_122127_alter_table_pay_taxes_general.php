<?php

use yii\db\Migration;

/**
 * Class m211116_122127_alter_table_pay_taxes_general
 */
class m211116_122127_alter_table_pay_taxes_general extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%pay_taxes_general}}', 'sms_1', $this->float());
        $this->addColumn('{{%pay_taxes_general}}', 'sms_2', $this->float());
        $this->addColumn('{{%pay_taxes_general}}', 'sms_3', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%pay_taxes_general}}', 'sms_1');
        $this->dropColumn('{{%pay_taxes_general}}', 'sms_2');
        $this->dropColumn('{{%pay_taxes_general}}', 'sms_3');
    }

}
