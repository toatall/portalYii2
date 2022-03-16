<?php

use yii\db\Migration;

/**
 * Class m220314_111636_create_table_execution_tasks
 */
class m220314_111636_create_table_execution_tasks extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%execute_tasks}}', [
            'id' => $this->primaryKey(),
            'org_code' => $this->string(5)->notNull(),
            'id_department' => $this->integer()->notNull(),
            'period' => $this->string(30)->notNull(),
            'period_year' => $this->smallInteger()->notNull(),
            'count_tasks' => $this->smallInteger(),
            'finish_tasks' => $this->smallInteger(),            
            'date_create' => $this->dateTime()->notNull(),
            'date_update' => $this->dateTime()->notNull(),
            'log_change' => $this->text(),
            'author' => $this->string(250)->notNull(),
        ]);
        $this->addForeignKey('fk__execute_tasks__org_code', '{{%execute_tasks}}', 'org_code', '{{%organization}}', 'code');
        $this->addForeignKey('fk__execute_tasks__id_department', '{{%execute_tasks}}', 'id_department', '{{%department}}', 'id');
        $this->addForeignKey('fk__execute_tasks__author', '{{%execute_tasks}}', 'author', '{{%user}}', 'username');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk__execute_tasks__org_code', '{{%execute_tasks}}');
        $this->dropForeignKey('fk__execute_tasks__id_department', '{{%execute_tasks}}');
        $this->dropForeignKey('fk__execute_tasks__author', '{{%execute_tasks}}');
        $this->dropTable('{{%execute_tasks}}');
    }
      
}
