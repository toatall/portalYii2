<?php

use yii\db\Migration;

/**
 * Class m230306_113534_table_beginner
 */
class m230306_113534_table_beginner extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%beginner}}', [
            'id' => $this->primaryKey(),
            'id_department' => $this->integer()->notNull(),
            'fio' => $this->string(500)->notNull(),  
            'date_employment' => $this->date(),  
            'description' => $this->text(),
            'js' => $this->text(),
            'css' => $this->text(),
            'date_create' => $this->integer()->notNull(),
            'date_update' => $this->integer()->notNull(),
            'author' => $this->string(250),
        ]);
        $this->addForeignKey('fk__beginner__id_department', '{{%beginner}}', 'id_department', 
            '{{%department}}', 'id', 'cascade');
        $this->addForeignKey('fk__beginner__author', '{{%beginner}}', 
            'author', '{{%user}}', 'username');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk__beginner__id_department', '{{%beginner}}');
        $this->dropForeignKey('fk__beginner__author', '{{%beginner}}');
        $this->dropTable('{{%beginner}}');
    }

   
}
