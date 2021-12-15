<?php

use yii\db\Migration;

/**
 * Class m211215_064352_alter_tabe_pay_taxes_add_new_columns_summ
 */
class m211215_064352_alter_tabe_pay_taxes_add_new_columns_summ extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('{{%pay_taxes_general}}', 'sms_fns', 'sum_left_all');
        $this->addColumn('{{%pay_taxes_general}}', 'sum_left_nifl', $this->float());
        $this->addColumn('{{%pay_taxes_general}}', 'sum_left_tn', $this->float());
        $this->addColumn('{{%pay_taxes_general}}', 'sum_left_zn', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('{{%pay_taxes_general}}', 'sum_left_all', 'sms_fns');
        $this->dropColumn('{{%pay_taxes_general}}', 'sum_left_nifl');
        $this->dropColumn('{{%pay_taxes_general}}', 'sum_left_tn');
        $this->dropColumn('{{%pay_taxes_general}}', 'sum_left_zn');
    }

}
