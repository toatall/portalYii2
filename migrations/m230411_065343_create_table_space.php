<?php

use yii\db\Migration;

/**
 * Class m230411_065343_create_table_space
 */
class m230411_065343_create_table_space extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%contest_space}}', [
            'id' => $this->primaryKey(),
            'org_code' => $this->string(5)->notNull(),
            'title' => $this->string(300)->notNull(),
            'date_create' => $this->integer(),
        ]);

        $this->createTable('{{%contest_space_like}}', [
            'id' => $this->primaryKey(),
            'id_space' => $this->integer()->notNull(),
            'author' => $this->string(250)->notNull(),
            'date_create' => $this->integer(),
        ]);
        $this->addForeignKey('fk__contest_space_like__id_space', '{{%contest_space_like}}', 'id_space', 
            '{{%contest_space}}', 'id', 'cascade');
        $this->addForeignKey('fk__contest_space_like__author', '{{%contest_space_like}}', 
            'author', '{{%user}}', 'username');    
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk__contest_space_like__id_space', '{{%contest_space_like}}');
        $this->dropForeignKey('fk__contest_space_like__author', '{{%contest_space_like}}');
        $this->dropTable('{{%contest_space_like}}');

        $this->dropTable('{{%contest_space}}');
    }
    
}
