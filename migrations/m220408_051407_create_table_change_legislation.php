<?php

use yii\db\Migration;

/**
 * Class m220408_051407_create_table_change_legislation
 */
class m220408_051407_create_table_change_legislation extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%change_legislation}}', [
            'id' => $this->primaryKey(),
            'type_doc' => $this->string(250)->notNull(),
            'date_doc' => $this->date(),
            'number_doc' => $this->string(250),
            'name' => $this->string('max')->notNull(),
            'date_doc_1' => $this->date(),
            'date_doc_2' => $this->date(),
            'date_doc_3' => $this->date(),
            'status_doc' => $this->string(250),
            'text' => $this->text(),
            'date_create' => $this->dateTime()->notNull(),
            'date_update' => $this->dateTime()->notNull(),
            'author' => $this->string(250)->notNull(),
            'log_change' => $this->text(),
        ]);
        $this->addForeignKey('fk__change_legislation__author', '{{%change_legislation}}', 'author', '{{%user}}', 'username'); 
    }   

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk__change_legislation__author', '{{%change_legislation}}');
        $this->dropTable('{{%change_legislation}}');
    }


}
