<?php

use yii\db\Migration;

/**
 * Class m220625_144435_create_contest_vote
 */
class m220625_144435_create_contest_vote extends Migration
{    

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {        

        $this->createTable('{{%contest_vote_main}}', [
            'id' => $this->primaryKey(),
            'tag' => $this->string(50)->notNull()->unique(),
            'title' => $this->string(300)->notNull(),
            'groups_vote' => $this->string(500)->notNull(),
            'date_start' => $this->dateTime()->notNull(),
            'date_end' => $this->dateTime()->notNull(),
            'date_create' => $this->dateTime()->notNull(),
        ]);

        $this->createTable('{{%contest_vote_data}}', [
            'id' => $this->primaryKey(),
            'id_contest_main' => $this->integer()->notNull(),
            'nomination' => $this->string(200)->notNull(),
            'title' => $this->string(1000)->notNull(),
            'description' => $this->text(),
            'file' => $this->string(500)->notNull(),
            'file_type' => $this->string(50)->notNull(),
            'date_create' => $this->dateTime()->notNull(),
            'date_update' => $this->dateTime()->notNull(),
            'author' => $this->string(250)->notNull(),
        ]);
        $this->addForeignKey('fk__contest_vote_data__id_contest_main', '{{%contest_vote_data}}', 
            'id_contest_main', '{{%contest_vote_main}}', 'id', 'cascade');
        $this->addForeignKey('fk__contest_vote_data__author', '{{%contest_vote_data}}', 
            'author', '{{%user}}', 'username');

        $this->createTable('{{%contest_vote_answer}}', [
            'id' => $this->primaryKey(),
            'id_contest_vote_data' => $this->integer()->notNull(),            
            'username' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime()->notNull(),
        ]);        
        $this->addForeignKey('fk__contest_vote_answer__id_contest_vote_data', '{{%contest_vote_answer}}', 
            'id_contest_vote_data', '{{%contest_vote_data}}', 'id', 'cascade');
        $this->addForeignKey('fk__contest_vote_answer__username', '{{%contest_vote_answer}}', 
            'username', '{{%user}}', 'username');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk__contest_vote_answer__id_contest_vote_data', '{{%contest_vote_answer}}');
        $this->dropForeignKey('fk__contest_vote_answer__username', '{{%contest_vote_answer}}');
        $this->dropTable('{{%contest_vote_answer}}');

        $this->dropForeignKey('fk__contest_vote_data__author', '{{%contest_vote_data}}');
        $this->dropForeignKey('fk__contest_vote_data__id_contest_main', '{{%contest_vote_data}}');
        $this->dropTable('{{%contest_vote_data}}');

        $this->dropTable('{{%contest_vote_main}}');
    }

    
}
