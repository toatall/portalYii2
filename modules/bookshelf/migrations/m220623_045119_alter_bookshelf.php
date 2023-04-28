<?php

use yii\db\Migration;

/**
 * Class m220623_045119_alter_bookshelf
 */
class m220623_045119_alter_bookshelf extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%book_shelf_recommend_read}}', [
            'id' => $this->primaryKey(),
            'fio' => $this->string(500)->notNull(),
            'writer' => $this->string(500),
            'book_name' => $this->string(500)->notNull(),
            'description' => $this->text(),
            'date_create' => $this->dateTime()->notNull(),
            'date_update' => $this->dateTime()->notNull(),
            'author' => $this->string(250)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%book_shelf_recommend_read}}');
    }
        
}
