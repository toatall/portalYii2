<?php

use yii\db\Migration;

/**
 * Class m201009_064236_create_table_department
 */
class m201009_064236_create_table_department extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // table department
        $this->createTable('{{%department}}', [
            'id' => $this->primaryKey(),
            'id_tree' => $this->integer()->notNull(),
            'id_organization' => $this->string(5)->notNull(),
            'department_index' => $this->string(2)->notNull(),
            'department_name' => $this->string(250)->notNull(),
            'use_card' => $this->boolean()->defaultValue(0),
            'general_page_type' => $this->smallInteger(),
            'general_page_id_tree' => $this->integer(),
            'author' => $this->string(250)->notNull(),
            'log_change' => $this->text(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'date_edit' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
        ]);
        $this->addForeignKey('fk__department__id_tree____tree', '{{%department}}', 'id_tree', '{{%tree}}', 'id');
        $this->addForeignKey('fk__department__id_organization____organization', '{{%department}}', 'id_organization', '{{%organization}}', 'code');
        $this->addForeignKey('fk__department__author____user', '{{%department}}', 'author', '{{%user}}', 'username_windows');

        // table department_card
        $this->createTable('{{%department_card}}', [
            'id' => $this->primaryKey(),
            'id_department' => $this->integer()->notNull(),
            'user_fio' => $this->string(500),
            'user_rank' => $this->string(200),
            'user_position' => $this->string(200),
            'user_telephone' => $this->string(50),
            'user_photo' => $this->string(250),
            'user_level' => $this->tinyInteger(),
            'sort_index' => $this->integer()->defaultValue(0),
            'log_change' => $this->text(),
            'user_resp' => $this->string('max'),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'date_edit' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
        ]);
        $this->addForeignKey('fk__department_card__id_department____department', '{{%department_card}}', 'id_department',
            '{{%department}}', 'id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drop table department_card
        $this->dropForeignKey('fk__department_card__id_department____department', '{{%department_card}}');
        $this->dropTable('{{%department_card}}');

        // drop table department
        $this->dropForeignKey('fk__department__id_tree____tree', '{{%department}}');
        $this->dropForeignKey('fk__department__id_organization____organization', '{{%department}}');
        $this->dropForeignKey('fk__department__author____user', '{{%department}}');
        $this->dropTable('{{%department}}');
    }

}
