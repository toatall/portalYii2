<?php

use yii\db\Migration;

/**
 * Class m211213_104913_alter_table_pay_taxes_general
 */
class m211213_104913_alter_table_pay_taxes_general extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%pay_taxes_general}}', 'sms_fns', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%pay_taxes_general}}', 'sms_fns');
    }

}
