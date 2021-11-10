<?php

use yii\db\Migration;

/**
 * Class m211110_122921_alter_table_organization_add_code_parent
 */
class m211110_122921_alter_table_organization_add_code_parent extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%organization}}', 'code_parent', $this->string(5));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%organization}}', 'code_parent');
    }
    
}
