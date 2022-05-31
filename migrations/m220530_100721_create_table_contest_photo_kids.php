<?php

use yii\db\Migration;

/**
 * Class m220530_100721_create_table_contest_photo_kids
 */
class m220530_100721_create_table_contest_photo_kids extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%contest_photo_kids}}', [
            'id' => $this->primaryKey(),
            'datetime_start' => $this->dateTime()->notNull(),
            'datetime_end' => $this->dateTime()->notNull(),
            'fio' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime()->notNull(),
        ]);

        $this->createTable('{{%contest_photo_kids_answer}}', [
            'id' => $this->primaryKey(),
            'id_photo_kids' => $this->integer()->notNull(),
            'fio' => $this->string(250)->notNull(),
            'username' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime()->notNull(),            
        ]);
        $this->addForeignKey('fk__contest_photo_kids_answer__id_photo_kids', '{{%contest_photo_kids_answer}}', 
            'id_photo_kids', '{{%contest_photo_kids}}', 'id');
        $this->addForeignKey('fk__contest_photo_kids_answer__username', '{{%contest_photo_kids_answer}}', 
            'username', '{{%user}}', 'username');

        $this->createTable('{{%contest_photo_kids_dic_employees}}', [
            'fio' => $this->string(500)->notNull()->unique(),
            'date_create' => $this->dateTime(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%contest_photo_kids_dic_employees}}');

        $this->dropForeignKey('fk__contest_photo_kids_answer__id_photo_kids', '{{%contest_photo_kids_answer}}');
        $this->dropForeignKey('fk__contest_photo_kids_answer__username', '{{%contest_photo_kids_answer}}');
        $this->dropTable('{{%contest_photo_kids_answer}}');

        $this->dropTable('{{%contest_photo_kids}}');
    }

}
