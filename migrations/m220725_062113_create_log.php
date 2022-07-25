<?php

use yii\db\Migration;

/**
 * Class m220725_062113_create_log
 */
class m220725_062113_create_log extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%history}}', [
            'id' => $this->primaryKey(),
            'url' => $this->string(400)->notNull(),
            'title' => $this->string(2000),
            'count_visits' => $this->integer()->notNull(),
            'date' => $this->date()->notNull(),
        ]);
        $this->createIndex('index__history__url', '{{%history}}', ['url', 'date'], true);
        
        
        $this->createTable('{{%history_detail}}', [
            'id' => $this->primaryKey(),
            'id_history' => $this->integer()->notNull(),
            'is_ajax' => $this->boolean(),
            'is_pjax' => $this->boolean(),            
            'method' => $this->string(30),
            'host' => $this->string(40),
            'ip' => $this->string(15),
            'date_create' => $this->integer()->notNull(),
            'author' => $this->string(250)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%history_detail}}');
        $this->dropTable('{{%history}}');
    }

}
