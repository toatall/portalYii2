<?php

use yii\db\Migration;

/**
 * Class m220204_095044_tables_education
 */
class m220204_095044_tables_education extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // курсы
        $this->createTable('{{%kadry_education}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(500)->notNull(),
            'description' => $this->text(),
            'description_full' => $this->text(),
            'thumbnail' => $this->string(500),
            'duration' => $this->string(100),
            'author' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime()->notNull(),
            'date_update' => $this->dateTime()->notNull(),
            'log_change' => $this->string('max')->notNull(),
        ]);
        $this->addForeignKey('fk__kadry_education__author', '{{%kadry_education}}', 'author', '{{%user}}', 'username');

        // данные по курсу (разделы)
        $this->createTable('{{%kadry_education_data}}', [
            'id' => $this->primaryKey(),
            'id_parent' => $this->integer(),
            'id_kadry_education' => $this->integer()->notNull(),
            'name' => $this->string(500)->notNull(),
            'description' => $this->text(),
            'thumbnail' => $this->string(500),
            'sort' => $this->integer()->defaultValue(0),
            'author' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime()->notNull(),
            'date_update' => $this->dateTime()->notNull(),
            'log_change' => $this->string('max')->notNull(),
        ]);
        $this->addForeignKey('fk__kadry_education_data__id_kadry_education', '{{%kadry_education_data}}', 'id_kadry_education', '{{%kadry_education}}', 'id', 'cascade');
        $this->addForeignKey('fk__kadry_education_data__author', '{{%kadry_education_data}}', 'author', '{{%user}}', 'username');

        // файлы в разделе курса
        $this->createTable('{{%kadry_education_data_files}}', [
            'id' => $this->primaryKey(),
            'id_kadry_education_data' => $this->integer()->notNull(),
            'filename' => $this->string(500)->notNull(),
            'title' => $this->string(250),
            'sort' => $this->integer()->defaultValue(0),
            'date_create' => $this->dateTime()->notNull(),
        ]);
        $this->addForeignKey('fk__kadry_education_data_files__id_kadry_education_data', 
            '{{%kadry_education_data_files}}', 'id_kadry_education_data', '{{%kadry_education_data}}', 'id', 'cascade');

        // пользователи, которые начали просмотр курса
        $this->createTable('{{%kadry_education_user}}', [
            'id' => $this->primaryKey(),
            'id_kadry_education' => $this->integer(),            
            'username' => $this->string(250)->notNull(),
            'percent' => $this->float(),
            'date_create' => $this->dateTime()->notNull(),
            'date_update' => $this->dateTime()->notNull(),
            'date_finish' => $this->dateTime(),
        ]);
        $this->addForeignKey('fk__kadry_education_user__username', '{{%kadry_education_user}}', 'username', '{{%user}}', 'username');
        $this->addForeignKey('fk__kadry_education_user__id_kadry_education', '{{%kadry_education_user}}', 'id_kadry_education', '{{%kadry_education}}', 'id', 'cascade');

        // разделы, которые просмотрел пользователь
        $this->createTable('{{%kadry_education_user_data}}', [
            'id' => $this->primaryKey(),
            'id_kadry_education_user' => $this->integer()->notNull(),
            'id_kadry_education_data' => $this->integer()->notNull(),
            'percent' => $this->float(),
            'username' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime()->notNull(),
            'date_update' => $this->dateTime()->notNull(),
        ]);
        $this->addForeignKey('fk__kadry_education_user_data__id_kadry_education_user', 
            '{{%kadry_education_user_data}}', 'id_kadry_education_user', '{{%kadry_education_user}}', 'id', 'cascade');
        $this->addForeignKey('fk__kadry_education_user_data__id_kadry_education_data', 
            '{{%kadry_education_user_data}}', 'id_kadry_education_data', '{{%kadry_education_data}}', 'id');

        // файлы, которые открывал пользователь
        $this->createTable('{{%kadry_education_user_data_files}}', [
            'id' => $this->primaryKey(),
            'id_kadry_education_user_data' => $this->integer()->notNull(),
            'id_kadry_education_data_files' => $this->integer()->notNull(),
            'username' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime()->notNull(),
        ]);
        $this->addForeignKey('fk__kadry_education_user_data_files__id_kadry_education_data_files', 
            '{{%kadry_education_user_data_files}}', 'id_kadry_education_data_files', '{{%kadry_education_data_files}}', 'id', 'cascade');
        $this->addForeignKey('fk__kadry_education_user_data_files__id_kadry_education_user_data', 
            '{{%kadry_education_user_data_files}}', 'id_kadry_education_user_data', '{{%kadry_education_user_data}}', 'id');
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // файлы, которые открывал пользователь
        $this->dropForeignKey('fk__kadry_education_user_data_files__id_kadry_education_data_files', '{{%kadry_education_user_data_files}}');
        $this->dropForeignKey('fk__kadry_education_user_data_files__id_kadry_education_user_data', '{{%kadry_education_user_data_files}}');
        $this->dropTable('{{%kadry_education_user_data_files}}');

        // разделы, которые просмотрел пользователь
        $this->dropForeignKey('fk__kadry_education_user_data__id_kadry_education_data', '{{%kadry_education_user_data}}');
        $this->dropForeignKey('fk__kadry_education_user_data__id_kadry_education_user', '{{%kadry_education_user_data}}');
        $this->dropTable('{{%kadry_education_user_data}}');

        // пользователи, которые начали просмотр курса
        $this->dropForeignKey('fk__kadry_education_user__username', '{{%kadry_education_user}}');
        $this->dropForeignKey('fk__kadry_education_user__id_kadry_education', '{{%kadry_education_user}}');
        $this->dropTable('{{%kadry_education_user}}');

        // файлы в разделе курса
        $this->dropForeignKey('fk__kadry_education_data_files__id_kadry_education_data', '{{%kadry_education_data_files}}');
        $this->dropTable('{{%kadry_education_data_files}}');

        // данные по курсу (разделы)
        $this->dropForeignKey('fk__kadry_education_data__id_kadry_education', '{{%kadry_education_data}}');
        $this->dropForeignKey('fk__kadry_education_data__author', '{{%kadry_education_data}}');
        $this->dropTable('{{%kadry_education_data}}');

        // курсы
        $this->dropForeignKey('fk__kadry_education__author', '{{%kadry_education}}');
        $this->dropTable('{{%kadry_education}}');

    }
    
}
