<?php

use yii\db\Migration;

/**
 * Class m230613_053103_alter_table_declare_campaign_usn
 */
class m230613_053103_alter_table_declare_campaign_usn extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%declare_campaign_usn}}', 'deadline', $this->date());
        $this->addColumn('{{%declare_campaign_usn}}', 'accrued_sum', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%declare_campaign_usn}}', 'deadline');
        $this->dropColumn('{{%declare_campaign_usn}}', 'accrued_sum');
    }

}
