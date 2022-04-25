<?php

use yii\db\Migration;

/**
 * Class m220425_065225_alter_table_change_legislation
 */
class m220425_065225_alter_table_change_legislation extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%change_legislation}}', 'is_anti_crisis', $this->boolean());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%change_legislation}}', 'is_anti_crisis');
    }
    
}
