<?php

use yii\db\Migration;

/**
 * Class m220316_093019_create_table_kadry_rating_test_result
 */
class m220316_093019_create_table_kadry_rating_test_result extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%kadry_test_result}}', [
            'id' => $this->primaryKey(),
            'org_code' => $this->string(5)->notNull(),
            'period' => $this->string(30)->notNull(),
            'period_year' => $this->smallInteger()->notNull(),
            'count_mark_five' => $this->integer()->notNull(),
            'count_mark_four' => $this->integer()->notNull(),
            'count_mark_three' => $this->integer()->notNull(),
            'count_kpk' => $this->integer()->notNull(),
            'avg_mark' => $this->float()->notNull(),
            'date_create' => $this->dateTime()->notNull(),
            'date_update' => $this->dateTime()->notNull(),
            'log_change' => $this->text(),
            'author' => $this->string(250)->notNull(),
        ]);
        $this->addForeignKey('fk__kadry_test_result__org_code', '{{%kadry_test_result}}', 'org_code', '{{%organization}}', 'code');       
        $this->addForeignKey('fk__kadry_test_result__author', '{{%kadry_test_result}}', 'author', '{{%user}}', 'username');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk__kadry_test_result__org_code', '{{%kadry_test_result}}');
        $this->dropForeignKey('fk__kadry_test_result__author', '{{%kadry_test_result}}');
        $this->dropTable('{{%kadry_test_result}}');
    }

}
