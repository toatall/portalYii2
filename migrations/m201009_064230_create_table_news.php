<?php

use yii\db\Migration;

/**
 * Class m201009_064230_create_table_news
 */
class m201009_064230_create_table_news extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%news}}', [
            'id' => $this->primaryKey(),
            'id_tree' => $this->integer()->notNull(),
            'id_organization' => $this->string(5)->notNull(),
            'title' => $this->string(500)->notNull(),
            'message1' => $this->string('max'),
            'message2' => $this->string('max')->notNull(),
            'author' => $this->string(250)->notNull(),
            'general_page' => $this->boolean()->defaultValue(0)->notNull(),
            'date_start_pub' => $this->date()->notNull(),
            'date_end_pub' => $this->date()->notNull(),
            'flag_enable' => $this->boolean()->defaultValue(1)->notNull(),
            'thumbail_title' => $this->string(),
            'thumbail_image' => $this->string(),
            'thumbail_text' => $this->string(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'date_edit' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'date_delete' => $this->dateTime(),
            'log_change' => $this->text(),
            'on_general_page' => $this->boolean()->defaultValue(0),            
            'tags' => $this->string(1000),
            'date_sort' => $this->dateTime()->notNull(),
        ]);
        $this->addForeignKey('fk__news__id_tree____tree', '{{%news}}', 'id_tree', '{{%tree}}', 'id');
        $this->addForeignKey('fk__news__id_organization____organization', '{{%news}}', 'id_organization', '{{%organization}}', 'code');
        $this->addForeignKey('fk__news__author____user', '{{%news}}', 'author', '{{%user}}', 'username_windows');        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {        
        $this->dropForeignKey('fk__news__id_tree____tree', '{{%news}}');
        $this->dropForeignKey('fk__news__id_organization____organization', '{{%news}}');
        $this->dropForeignKey('fk__news__author____user', '{{%news}}');
        $this->dropTable('{{%news}}');
    }

}
