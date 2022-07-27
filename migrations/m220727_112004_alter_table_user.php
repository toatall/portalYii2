<?php

use yii\db\Migration;

/**
 * Class m220727_112004_alter_table_user
 */
class m220727_112004_alter_table_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'last_action', $this->string(500));
        $this->addColumn('{{%user}}', 'last_action_time', $this->integer());        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'last_action');
        $this->dropColumn('{{%user}}', 'last_action_time');
    }
    
}
