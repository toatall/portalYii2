<?php

use yii\db\Migration;

/**
 * Class m211221_090335_table_create_lifehack
 */
class m211221_090335_table_create_lifehack extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%lifehack_tags}}', [
            'id' => $this->primaryKey(),
            'tag' => $this->string(100)->notNull()->unique(),
            'date_create' => $this->dateTime()->notNull(),
        ]);

        $this->createTable('{{%lifehack}}', [
            'id' => $this->primaryKey(),
            'org_code' => $this->string(5)->notNull(),            
            'tags' => $this->string(2000)->notNull(),     
            'title' => $this->string(2000)->notNull(),   
            'text' => $this->text(),
            'author_name' => $this->string(500),
            'date_create' => $this->dateTime(),
            'date_update' => $this->dateTime(),
            'username' => $this->string(250),
            'log_change' => $this->string('max'),            
        ]);

        $this->createTable('{{%lifehack_file}}', [
            'id' => $this->primaryKey(),
            'id_lifehack' => $this->integer()->notNull(),
            'filename' => $this->string(500)->notNull(),
            'file_type_icon' => $this->string(15),
            'date_create' => $this->dateTime(),
            'count_download' => $this->integer()->defaultValue(0),
            'username' => $this->string(250),
        ]);
        $this->addForeignKey('fk__lifehack_file__id_lifehack', '{{%lifehack_file}}', 'id_lifehack', 
            '{{%lifehack}}', 'id', 'cascade');

        $this->createTable('{{%lifehack_file_download}}', [
            'id' => $this->primaryKey(),            
            'id_lifehack_file' => $this->integer()->notNull(),
            'date_create' => $this->dateTime(),
            'username' => $this->string(250),
        ]);
        $this->addForeignKey('fk__lifehack_file_download__id_lifehack_file', '{{%lifehack_file_download}}', 'id_lifehack_file', 
            '{{%lifehack_file}}', 'id', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk__lifehack_file_download__id_lifehack_file', '{{%lifehack_file_download}}');
        $this->dropTable('{{%lifehack_file_download}}');

        $this->dropForeignKey('fk__lifehack_file__id_lifehack', '{{%lifehack_file}}');
        $this->dropTable('{{%lifehack_file}}');

        $this->dropTable('{{%lifehack}}');

        $this->dropTable('{{%lifehack_tags}}');
    }
    
}
