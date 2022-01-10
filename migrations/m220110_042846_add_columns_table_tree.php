<?php

use yii\db\Migration;

/**
 * Class m220110_042846_add_columns_table_tree
 */
class m220110_042846_add_columns_table_tree extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tree}}', 'is_url', $this->boolean());
        $this->addColumn('{{%tree}}', 'url', $this->string(500));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%tree}}', 'is_url');
        $this->dropColumn('{{%tree}}', 'url');
    }

    
}
