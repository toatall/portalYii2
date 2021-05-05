<?php

use yii\db\Migration;

/**
 * Class m210215_045634_create_table_test_opinion
 */
class m210215_045634_create_table_test_opinion extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%test_result_opinion}}', [
            'id' => $this->primaryKey(),
            'id_test' => $this->integer()->notNull(),           
            'rating' => $this->smallInteger()->notNull(),
            'note' => $this->text(),
            'author' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()'),
        ]);        
        $this->addForeignKey('fk-test_result_opinion-id_test', '{{%test_result_opinion}}', 'id_test', '{{%test}}', 'id', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {        
        $this->dropForeignKey('fk-test_result_opinion-id_test', '{{%test_result_opinion}}');
        $this->dropTable('{{%test_result_opinion}}');
    }

}
