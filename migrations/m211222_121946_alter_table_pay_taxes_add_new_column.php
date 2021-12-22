<?php

use yii\db\Migration;

/**
 * Class m211222_121946_alter_table_pay_taxes_add_new_column
 */
class m211222_121946_alter_table_pay_taxes_add_new_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%pay_taxes_general}}', 'growth_sms', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%pay_taxes_general}}', 'growth_sms');
    }

}
