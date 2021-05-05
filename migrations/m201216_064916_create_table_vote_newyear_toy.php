<?php

use yii\db\Migration;

/**
 * Class m201216_064916_create_table_vote_newyear_toy
 */
class m201216_064916_create_table_vote_newyear_toy extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%vote_newyear_toy}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(1000)->notNull(),
            'description' => $this->string('max'),
            'code_org' => $this->string(5)->notNull(),
            'department' => $this->string(250),
            'date_create' => $this->dateTime()->defaultExpression('getdate()'),
        ]);
        $this->createTable('{{%vote_newyear_toy_file}}', [
            'id' => $this->primaryKey(),
            'id_vote_newyear_toy' => $this->integer(),
            'file_name' => $this->string(500)->notNull(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()'),
        ]);
        $this->addForeignKey('fk__vote_newyear_toy_file__id_vote_newyear_toy____vote_newyear_toy', '{{%vote_newyear_toy_file}}', 'id_vote_newyear_toy',
            '{{%vote_newyear_toy}}', 'id', 'cascade');

        $this->createTable('{{%vote_newyear_toy_answer}}', [
            'id' => $this->primaryKey(),
            'id_vote_newyear_toy' => $this->integer()->notNull(),
            'username' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()'),
        ]);
        $this->addForeignKey('fk__vote_newyear_toy_answer__id_vote_newyear_toy____vote_newyear_toy', '{{%vote_newyear_toy_answer}}', 'id_vote_newyear_toy',
            '{{%vote_newyear_toy}}', 'id', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk__vote_newyear_toy_answer__id_vote_newyear_toy____vote_newyear_toy', '{{%vote_newyear_toy_answer}}');
        $this->dropTable('{{%vote_newyear_toy_answer}}');

        $this->dropForeignKey('fk__vote_newyear_toy_file__id_vote_newyear_toy____vote_newyear_toy', '{{%vote_newyear_toy_file}}');
        $this->dropTable('{{%vote_newyear_toy_file}}');

        $this->dropTable('{{%vote_newyear_toy}}');
    }

}
