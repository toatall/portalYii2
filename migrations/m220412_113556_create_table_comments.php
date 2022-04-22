<?php

use yii\db\Migration;

/**
 * Class m220412_113556_create_table_comments
 */
class m220412_113556_create_table_comments extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%comment}}', [
            'id' => $this->primaryKey(),            
            'id_parent' => $this->integer(),
            'id_reply' => $this->integer(),
            'bind_hash' => $this->string(50)->notNull(),
            'url' => $this->string(1000)->notNull(), 
            'username' => $this->string(250)->notNull(),
            'text' => $this->text(),
            'date_create' => $this->dateTime()->notNull(),
            'date_update' => $this->dateTime()->notNull(),
            'date_delete' => $this->dateTime(),
            'log_change' => $this->text(),
        ]);
        $this->addForeignKey('fk__comment__username', '{{%comment}}', 'username', '{{%user}}', 'username'); 
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk__comment__username', '{{%comment}}');
        $this->dropTable('{{%comment}}');
    }

}
