<?php

use yii\db\Migration;

/**
 * Class m220217_043202_create_tables_bookshelf
 */
class m220217_043202_create_tables_bookshelf extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // 1 календарь литературных дат
        $this->createTable('{{%book_shelf_calendar}}', [
            'id' => $this->primaryKey(),
            'date_birthday' => $this->date()->notNull(),
            'date_die' => $this->date(),
            'writer' => $this->string(500)->notNull(),
            'photo' => $this->string(500),
            'description' => $this->text(),
            'author' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime()->notNull(),
            'date_update' => $this->dateTime()->notNull(),
            'log_change' => $this->text()->notNull(), 
        ]);
        $this->addForeignKey('fk__book_shelf_calendar__author', '{{%book_shelf_calendar}}', 'author', '{{%user}}', 'username');


        
        // 2 Что взять на книжной полке

        // 2.2 места
        $this->createTable('{{%book_shelf_place}}', [
            'id' => $this->primaryKey(),            
            'place' => $this->string(100)->notNull()->unique(),
            'username' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime()->notNull(),
        ]);
        $this->addForeignKey('fk__book_shelf_place__username', '{{%book_shelf_place}}', 'username', '{{%user}}', 'username');

        $this->createTable('{{%book_shelf}}', [
            'id' => $this->primaryKey(),
            'writer' => $this->string(500),
            'title' => $this->string(1000)->notNull(),
            'rating' => $this->float(),
            'place' => $this->string(100)->notNull(),
            'photo' => $this->string(500),
            'description' => $this->text(),
            'date_received' => $this->date(),
            'date_away' => $this->date(),
            'book_status' => $this->smallInteger()->notNull(),
            'author' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime()->notNull(),
            'date_update' => $this->dateTime()->notNull(),
            'log_change' => $this->text()->notNull(), 
        ]);
        $this->addForeignKey('fk__book_shelf__author', '{{%book_shelf}}', 'author', '{{%user}}', 'username');        


        // 2.1 рейтинг книги
        $this->createTable('{{%book_shelf_rating}}', [
            'id' => $this->primaryKey(),
            'id_book_shelf' => $this->integer()->notNull(),
            'rating' => $this->float(),
            'username' => $this->string(250)->notNull(),
            'date_create' => $this->date(),
        ]);
        $this->addForeignKey('fk__book_shelf_rating__id_book_shelf', '{{%book_shelf_rating}}', 'id_book_shelf', '{{%book_shelf}}', 'id', 'cascade');
        $this->addForeignKey('fk__book_shelf_rating__username', '{{%book_shelf_rating}}', 'username', '{{%user}}', 'username');        

            
        // 3 Что сейчас читает... ФИО
        $this->createTable('{{%book_shelf_what_reading}}', [
            'id' => $this->primaryKey(),
            'fio' => $this->string(250)->notNull(),
            'writer' => $this->string(500)->notNull(),
            'title' => $this->string(1000)->notNull(),
            'author' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime()->notNull(),
            'date_update' => $this->dateTime()->notNull(),
            'log_change' => $this->text()->notNull(),
        ]);
        $this->addForeignKey('fk__book_shelf_what_reading__author', '{{%book_shelf_what_reading}}', 'author', '{{%user}}', 'username');       
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // 3 Что сейчас читает... ФИО
        $this->dropForeignKey('fk__book_shelf_what_reading__author', '{{%book_shelf_what_reading}}');
        $this->dropTable('{{%book_shelf_what_reading}}');        

        // 2 Что взять на книжной полке
        $this->dropForeignKey('fk__book_shelf_rating__id_book_shelf', '{{%book_shelf_rating}}');
        $this->dropForeignKey('fk__book_shelf_rating__username', '{{%book_shelf_rating}}');
        $this->dropTable('{{%book_shelf_rating}}');

        $this->dropForeignKey('fk__book_shelf__author', '{{%book_shelf}}');
        $this->dropTable('{{%book_shelf}}');

        $this->dropForeignKey('fk__book_shelf_place__username', '{{%book_shelf_place}}');
        $this->dropTable('{{%book_shelf_place}}');

        // 1 календарь литературных дат
        $this->dropForeignKey('fk__book_shelf_calendar__author', '{{%book_shelf_calendar}}');
        $this->dropTable('{{%book_shelf_calendar}}');

    }

}
