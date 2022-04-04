<?php

use yii\db\Migration;

/**
 * Class m220404_093657_create_table_quiz
 */
class m220404_093657_create_table_quiz extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%quiz}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(2000)->notNull(),
            'date_create' => $this->dateTime()->notNull(),
            'date_update' => $this->dateTime()->notNull(),
            'author' => $this->string(250)->notNull(),
        ]);
        $this->addForeignKey('fk__quiz__author', '{{%quiz}}', 'author', '{{%user}}', 'username');       

        $this->createTable('{{%quiz_question}}', [
            'id' => $this->primaryKey(),
            'id_quiz' => $this->integer()->notNull(),
            'type_question' => $this->smallInteger()->notNull(),
            'name' => $this->string(2000)->notNull(),
            'date_create' => $this->dateTime()->notNull(),
            'date_update' => $this->dateTime()->notNull(),
            'author' => $this->string(250)->notNull(),
        ]);
        $this->addForeignKey('fk__quiz_question__id_quiz', '{{%quiz_question}}', 'id_quiz', '{{%quiz}}', 'id', 'cascade');
        $this->addForeignKey('fk__quiz_question__author', '{{%quiz_question}}', 'author', '{{%user}}', 'username');             

        $this->createTable('{{%quiz_result}}', [
            'id' => $this->primaryKey(),
            'id_quiz' => $this->integer()->notNull(),
            'username' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime()->notNull(),
        ]);
        $this->addForeignKey('fk__quiz_result__author', '{{%quiz_result}}', 'username', '{{%user}}', 'username'); 

        $this->createTable('{{%quiz_result_question}}', [
            'id' => $this->primaryKey(),
            'id_result' => $this->integer()->notNull(),
            'id_question' => $this->integer()->notNull(),
            'value' => $this->string('max')->notNull(),
            'date_create' => $this->dateTime()->notNull(),           
            'author' => $this->string(250)->notNull(),
        ]);
        $this->addForeignKey('fk__quiz_result_question__author', '{{%quiz_result_question}}', 'author', '{{%user}}', 'username'); 
        $this->addForeignKey('fk__quiz_result_question__id_result', '{{%quiz_result_question}}', 'id_result', '{{%quiz_result}}', 'id', 'cascade');
        $this->addForeignKey('fk__quiz_result_question__id_question', '{{%quiz_result_question}}', 'id_question', '{{%quiz_question}}', 'id', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk__quiz_result_question__author', '{{%quiz_result_question}}');
        $this->dropForeignKey('fk__quiz_result_question__id_result', '{{%quiz_result_question}}');
        $this->dropForeignKey('fk__quiz_result_question__id_question', '{{%quiz_result_question}}');
        $this->dropTable('{{%quiz_result_question}}');

        $this->dropForeignKey('fk__quiz_result__author', '{{%quiz_result}}');
        $this->dropTable('{{%quiz_result}}');

        $this->dropForeignKey('fk__quiz_question__author', '{{%quiz_question}}');
        $this->dropForeignKey('fk__quiz_question__id_quiz', '{{%quiz_question}}');
        $this->dropTable('{{%quiz_question}}');

        $this->dropForeignKey('fk__quiz__author', '{{%quiz}}');
        $this->dropTable('{{%quiz}}');
    }
    
}
