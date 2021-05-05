<?php

use yii\db\Migration;

/**
 * Class m201009_100529_create_table_vote
 */
class m201009_100529_create_table_vote extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // table vote_main
        $this->createTable('{{%vote_main}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(250)->notNull(),
            'date_start' => $this->dateTime()->notNull(),
            'date_end' => $this->dateTime()->notNull(),
            'organizations' => $this->string(100)->notNull(),
            'multi_answer' => $this->boolean(),
            'on_general_page' => $this->boolean(),
            'description' => $this->string('max'),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'date_edit' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'log_change' => $this->string('max')->notNull(),
        ]);

        // table vote_question
        $this->createTable('{{%vote_question}}', [
            'id' => $this->primaryKey(),
            'id_main' => $this->integer()->notNull(),
            'count_votes' => $this->integer(),
            'text_question' => $this->string('max')->notNull(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'date_edit' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'log_change' => $this->string('max')->notNull(),
        ]);
        $this->addForeignKey('fk__vote_question__id_main____vote_main', '{{%vote_question}}',
            'id_main', '{{%vote_main}}', 'id', 'cascade');

        // table vote_answer
        $this->createTable('{{%vote_answer}}', [
            'id' => $this->primaryKey(),
            'id_question' => $this->integer()->notNull(),
            'username' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
        ]);
        $this->addForeignKey('fk__vote_answer__id_question____vote_question', '{{%vote_answer}}', 'id_question',
            '{{%vote_question}}', 'id', 'cascade');
        $this->addForeignKey('fk__vote_answer__username____user', '{{%vote_answer}}', 'username', '{{%user}}', 'username_windows');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drop vote_answer
        $this->dropForeignKey('fk__vote_answer__username____user', '{{%vote_answer}}');
        $this->dropForeignKey('fk__vote_answer__id_question____vote_question', '{{%vote_answer}}');
        $this->dropTable('{{%vote_answer}}');

        // drop vote_question
        $this->dropForeignKey('fk__vote_question__id_main____vote_main', '{{%vote_question}}');
        $this->dropTable('{{%vote_question}}');

        // drop vote_main
        $this->dropTable('{{%vote_main}}');
    }

}
