<?php

use yii\db\Migration;

/**
 * Class m220407_124305_create_table_protocol
 */
class m220407_124305_create_table_protocol extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%protocol}}', [
            'id' => $this->primaryKey(),
            'type_protocol' => $this->string(250)->notNull(),
            'date' => $this->date()->notNull(),
            'number' => $this->string(150)->notNull(),
            'name' => $this->string(2500)->notNull(),
            'executor'  => $this->string(2500),
            'execute_description' => $this->text(),
            'date_create' => $this->dateTime()->notNull(),
            'date_update' => $this->dateTime()->notNull(),
            'author' => $this->string(250)->notNull(),
        ]);
        $this->addForeignKey('fk__protocol__author', '{{%protocol}}', 'author', '{{%user}}', 'username'); 
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk__protocol__author', '{{%protocol}}');
        $this->dropTable('{{%protocol}}');
    }

}
