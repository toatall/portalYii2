<?php

use yii\db\Migration;

/**
 * Class m210727_073535_alter_table_op_group
 */
class m210727_073535_alter_table_op_group extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%op_group}}', 'view_documents_section', $this->boolean());
        $this->addColumn('{{%op_group}}', 'view_arbitration_section', $this->boolean());
        $this->execute('update {{%op_group}} set view_documents_section=1, view_arbitration_section=1');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%op_group}}', 'view_documents_section');
        $this->dropColumn('{{%op_group}}', 'view_arbitration_section');
    }
    
}
