<?php

use yii\db\Migration;

/**
 * Class m220614_094812_alter_execute_tasks
 */
class m220614_094812_alter_execute_tasks extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%execute_tasks_detail}}', [
            'id' => $this->primaryKey(),
            'id_task' => $this->integer()->notNull(),
            'name' => $this->string(2000)->notNull(),
            'count_tasks' => $this->smallInteger(),
            'finish_tasks' => $this->smallInteger(),
            'date_create' => $this->dateTime()->notNull(),
            'date_update' => $this->dateTime()->notNull(),
            'author' => $this->string(250)->notNull(),
        ]);
        $this->addForeignKey('fk__execute_tasks_detail__id_task', '{{%execute_tasks_detail}}', 'id_task', '{{%execute_tasks}}', 'id', 'cascade');

        $this->createTable('{{%execute_tasks_description_department}}', [
            'id' => $this->primaryKey(),
            'id_department' => $this->integer()->unique()->notNull(),
            'fio' => $this->string(500)->notNull(),
            'telephone' => $this->string(200),
            'post' => $this->string(500),
            'rank' => $this->string(500),            
            'description' => $this->text(),
            'date_create' => $this->dateTime()->notNull(),
            'date_update' => $this->dateTime()->notNull(),
            'author' => $this->string(250)->notNull(),
        ]);
        $this->addForeignKey('fk__execute_tasks_description_department__id_department', '{{%execute_tasks_description_department}}', 
            'id_department', '{{%department}}', 'id', 'cascade');

        $this->createTable('{{%execute_tasks_description_organization}}', [
                'id' => $this->primaryKey(),
                'code_org' => $this->string(5)->unique()->notNull(),               
                'fio' => $this->string(500)->notNull(),
                'telephone' => $this->string(200),
                'post' => $this->string(500),
                'rank' => $this->string(500),            
                'description' => $this->text(),
                'date_create' => $this->dateTime()->notNull(),
                'date_update' => $this->dateTime()->notNull(),
                'author' => $this->string(250)->notNull(),
            ]);
            $this->addForeignKey('fk__execute_tasks_description_organization__code_org', '{{%execute_tasks_description_organization}}', 
                'code_org', '{{%organization}}', 'code', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk__execute_tasks_description_organization__code_org', '{{%execute_tasks_description_organization}}');
        $this->dropTable('{{%execute_tasks_description_organization}}');

        $this->dropForeignKey('fk__execute_tasks_description_department__id_department', '{{%execute_tasks_description_department}}');
        $this->dropTable('{{%execute_tasks_description_department}}');

        $this->dropForeignKey('fk__execute_tasks_detail__id_task', '{{%execute_tasks_detail}}');
        $this->dropTable('{{%execute_tasks_detail}}');
    }

}
