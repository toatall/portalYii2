<?php

use yii\db\Migration;

/**
 * Class m220525_120553_create_table_manual_neighbor
 */
class m220525_070553_create_table_manual_neighbor extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%contest_manual_neighbor}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(500)->notNull(),
            'department' => $this->string(250)->notNull(),
            'file' => $this->string(500),
            'count_votes_1' => $this->integer(), // Разберётся и ребенок
            'count_votes_2' => $this->integer(), // Охват аудитории
            'count_votes_3' => $this->integer(), // Глаза разбегаются
            'date_create' => $this->dateTime()->notNull(),
        ]);
        $this->createTable('{{%contest_manual_neighbor_vote}}', [
            'id' => $this->primaryKey(),
            'id_manual_neighbor_1' => $this->integer()->notNull(),
            'id_manual_neighbor_2' => $this->integer()->notNull(),
            'id_manual_neighbor_3' => $this->integer()->notNull(),
            'username' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime()->notNull(),
        ]);
        // $this->addForeignKey('fk__contest_manual_neighbor_vote__id_manual_neighbor_1', '{{%contest_manual_neighbor_vote}}', 
        //     'id_manual_neighbor_1', '{{%contest_manual_neighbor}}', 'id', 'cascade');
        // $this->addForeignKey('fk__contest_manual_neighbor_vote__id_manual_neighbor_2', '{{%contest_manual_neighbor_vote}}', 
        //     'id_manual_neighbor_2', '{{%contest_manual_neighbor}}', 'id', 'cascade');
        // $this->addForeignKey('fk__contest_manual_neighbor_vote__id_manual_neighbor_3', '{{%contest_manual_neighbor_vote}}', 
        //     'id_manual_neighbor_3', '{{%contest_manual_neighbor}}', 'id', 'cascade');
        $this->addForeignKey('fk__contest_manual_neighbor_vote__username', '{{%contest_manual_neighbor_vote}}', 
            'username', '{{%user}}', 'username');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk__contest_manual_neighbor_vote__username', '{{%contest_manual_neighbor_vote}}');
        // $this->dropForeignKey('fk__contest_manual_neighbor_vote__id_manual_neighbor', '{{%contest_manual_neighbor_vote}}');
        $this->dropTable('{{%contest_manual_neighbor_vote}}');

        $this->dropTable('{{%contest_manual_neighbor}}');
    }

}
