<?php

use yii\db\Migration;

/**
 * Class m210618_094017_alter_table_conference
 */
class m210618_094017_alter_table_conference extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%conference}}', 'status', $this->string(15));
        $this->addColumn('{{%conference}}', 'editor', $this->string(250));
        
        $this->execute('update {{%conference}} set status=:status, editor=:editor', [
            ':status' => 'complete',
            ':editor' => 'system',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%conference}}', 'status');
        $this->dropColumn('{{%conference}}', 'editor');
    }
    
}
