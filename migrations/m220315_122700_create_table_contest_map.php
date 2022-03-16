<?php

use yii\db\Migration;

/**
 * Class m220315_122700_create_table_contest_map
 */
class m220315_122700_create_table_contest_map extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%contest_map}}', [
            'id' => $this->primaryKey(),
            'date_show' => $this->date()->unique()->notNull(),
            'point_path' => $this->string('max')->notNull(),
            'place_path' => $this->string('max')->notNull(),
            'place_name' => $this->string(500)->notNull(),
            'text_question' => $this->text(),
            'date_create' => $this->dateTime(),            
        ]);
        $this->createTable('{{%contest_map_answer}}', [
            'id' => $this->primaryKey(),
            'id_contest_map' => $this->integer()->notNull(),
            'place_name' => $this->string(500)->notNull(),
            'username' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime()->notNull(),            
        ]);
        $this->addForeignKey('fk__contest_map__id_contest_map', '{{%contest_map_answer}}', 'id_contest_map', '{{%contest_map}}', 'id', 'cascade');
        $this->addForeignKey('fk__contest_map__username', '{{%contest_map_answer}}', 'username', '{{%user}}', 'username');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk__contest_map__id_contest_map', '{{%contest_map_answer}}');
        $this->dropForeignKey('fk__contest_map__username', '{{%contest_map_answer}}');
        $this->dropTable('{{%contest_map_answer}}');

        $this->dropTable('{{%contest_map}}');
    }
       
}
