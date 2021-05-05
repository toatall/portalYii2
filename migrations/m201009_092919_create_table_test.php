<?php

use yii\db\Migration;

/**
 * Class m201009_092919_create_table_test
 */
class m201009_092919_create_table_test extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // table test
        $this->createTable('{{%test}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(250)->notNull(),
            'date_start' => $this->dateTime()->notNull(),
            'date_end' => $this->dateTime()->notNull(),
            'count_attempt' => $this->smallInteger(),
            'count_questions' => $this->smallInteger(),
            'description' => $this->string('max'),
            'time_limit' => $this->time(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'author' => $this->string(250)->notNull(),
        ]);
        $this->addForeignKey('fk__test__author____user', '{{%test}}', 'author', '{{%user}}', 'username_windows');

        // table test_question
        $this->createTable('{{%test_question}}', [
            'id' => $this->primaryKey(),
            'id_test' => $this->integer()->notNull(),
            'name' => $this->string(2500)->notNull(),
            'type_question' => $this->smallInteger(),
            'attach_file' => $this->string(200),
            'weight' => $this->smallInteger(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'author' => $this->string(250)->notNull(),
        ]);
        $this->addForeignKey('fk__test_question__id_test____test', '{{%test_question}}', 'id_test', '{{%test}}', 'id', 'cascade');
        $this->addForeignKey('fk__test_question__author____user', '{{%test_question}}', 'author', '{{%user}}', 'username_windows');

        // table test_answer
        $this->createTable('{{%test_answer}}', [
            'id' => $this->primaryKey(),
            'id_test_question' => $this->integer()->notNull(),
            'name' => $this->string(2500)->notNull(),
            'attach_file' => $this->string(200),
            'weight' => $this->smallInteger(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
        ]);
        $this->addForeignKey('fk__test_answer__id_test_question____test_question', '{{%test_answer}}', 'id_test_question',
            '{{%test_question}}', 'id', 'cascade');

        // table test_result
        $this->createTable('{{%test_result}}', [
            'id' => $this->primaryKey(),
            'id_test' => $this->integer()->notNull(),
            'username' => $this->string(250)->notNull(),
            'org_code' => $this->string(5)->notNull(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),

        ]);
        $this->addForeignKey('fk__test_result__id_test____test', '{{%test_result}}', 'id_test', '{{%test}}', 'id', 'cascade');
        $this->addForeignKey('fk__test_result__org_code____organization', '{{%test_result}}', 'org_code', '{{%organization}}', 'code');
        $this->addForeignKey('fk__test_result__username____user', '{{%test_result}}', 'username', '{{%user}}', 'username_windows');

        // table test_result_question
        $this->createTable('{{%test_result_question}}', [
            'id' => $this->primaryKey(),
            'id_test_result' => $this->integer()->notNull(),
            'id_test_question' => $this->integer()->notNull(),
            'weight' => $this->smallInteger(),
            'is_right' => $this->boolean(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
        ]);
        $this->addForeignKey('fk__test_result_question__id_test_result____test_result', '{{%test_result_question}}',
            'id_test_result', '{{%test_result}}', 'id', 'cascade');
        $this->addForeignKey('fk__test_result_question__id_test_question____test_question', '{{%test_result_question}}',
            'id_test_question', '{{%test_question}}', 'id');

        // table test_result_answer
        $this->createTable('{{%test_result_answer}}', [
            'id' => $this->primaryKey(),
            'id_test_result_question' => $this->integer()->notNull(),
            'id_test_answer' => $this->integer()->notNull(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
        ]);
        $this->addForeignKey('fk__test_result_answer__id_test_result_question____test_result_question', '{{%test_result_answer}}',
            'id_test_result_question', '{{%test_result_question}}', 'id', 'cascade');
        $this->addForeignKey('fk__test_result_answer__id_test_answer____test_answer', '{{%test_result_answer}}',
            'id_test_answer', '{{%test_answer}}', 'id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        // drop test_result_answer
        $this->dropForeignKey('fk__test_result_answer__id_test_result_question____test_result_question', '{{%test_result_answer}}');
        $this->dropForeignKey('fk__test_result_answer__id_test_answer____test_answer', '{{%test_result_answer}}');
        $this->dropTable('{{%test_result_answer}}');

        // drop test_result_question
        $this->dropForeignKey('fk__test_result_question__id_test_result____test_result', '{{%test_result_question}}');
        $this->dropForeignKey('fk__test_result_question__id_test_question____test_question', '{{%test_result_question}}');
        $this->dropTable('{{%test_result_question}}');

        // drop test_result
        $this->dropForeignKey('fk__test_result__id_test____test', '{{%test_result}}');
        $this->dropForeignKey('fk__test_result__username____user', '{{%test_result}}');
        $this->dropForeignKey('fk__test_result__org_code____organization', '{{%test_result}}');
        $this->dropTable('{{%test_result}}');

        // drop test_answer
        $this->dropForeignKey('fk__test_answer__id_test_question____test_question', '{{%test_answer}}');
        $this->dropTable('{{%test_answer}}');

        // drop test_question
        $this->dropForeignKey('fk__test_question__author____user', '{{%test_question}}');
        $this->dropForeignKey('fk__test_question__id_test____test', '{{%test_question}}');
        $this->dropTable('{{%test_question}}');

        // drop test
        $this->dropForeignKey('fk__test__author____user', '{{%test}}');
        $this->dropTable('{{%test}}');
    }

}
