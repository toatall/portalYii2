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
            'url' => $this->string(100)->notNull()->unique(),
            'count_visits' => $this->integer()->notNull(),
            'date_create' => $this->integer()->notNull(),
        ]);
        $this->createIndex('index__history__url', '{{%history}}', 'url');
        
        $this->createTable('{{%history_detail}}', [
            'id' => $this->primaryKey(),
            'id_history' => $this->integer()->notNull(),
            'is_ajax' => $this->boolean(),
            'is_pjax' => $this->boolean(),
            'method' => $this->string(30),            
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
