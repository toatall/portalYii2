<?php

use yii\db\Migration;

/**
 * Class m201009_155513_create_table_op
 */
class m201009_155513_create_table_op extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // op_group
        $this->createTable('{{%op_group}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
        ]);

        // op_files
        $this->createTable('{{%op_files}}', [
            'id' => $this->primaryKey(),
            'id_op_group' => $this->integer()->notNull(),
            'type_section' => $this->smallInteger()->notNull(),
            'name' => $this->string(1000)->notNull(),
            'file_name' => $this->string(1000),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
        ]);
        $this->addForeignKey('fk__op_files__id_op_group____op_group', '{{%op_files}}',
            'id_op_group', '{{%op_group}}', 'id', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drop op_files
        $this->dropForeignKey('fk__op_files__id_op_group____op_group', '{{%op_files}}');
        $this->dropTable('{{%op_files}}');

        // drop op_group
        $this->dropTable('{{%op_group}}');

    }

}
