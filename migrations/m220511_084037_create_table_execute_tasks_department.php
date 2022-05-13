<?php

use yii\db\Migration;

/**
 * Class m220511_084037_create_table_execute_tasks_department
 */
class m220511_084037_create_table_execute_tasks_department extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%execute_tasks_department}}', [
            'id' => $this->primaryKey(),
            'id_department' => $this->integer()->notNull(),
            'type_index' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime()->notNull(),
            'date_update' => $this->dateTime()->notNull(),
            'author' => $this->string(250)->notNull(),
        ]);
        $this->execute('ALTER TABLE {{%execute_tasks_department}} ADD CONSTRAINT unique_execute_tasks_department UNIQUE (id_department, type_index)');
        $this->addForeignKey('fk__execute_tasks_department__id_department', '{{%execute_tasks_department}}', 'id_department', '{{%department}}', 'id', 'cascade');
        $this->addForeignKey('fk__execute_tasks_department__author', '{{%execute_tasks_department}}', 'author', '{{%user}}', 'username'); 
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk__execute_tasks_department__author', '{{%execute_tasks_department}}');
        $this->dropForeignKey('fk__execute_tasks_department__id_department', '{{%execute_tasks_department}}');
        $this->dropTable('{{%execute_tasks_department}}');
    }
   
}
