<?php

use yii\db\Migration;

/**
 * Class m220113_103212_create_table_fort_boyard
 */
class m220113_103212_create_table_fort_boyard extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%fort_boyard_teams}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime(),
        ]);

        $this->createTable('{{%fort_boyard_access}}', [
            'id' => $this->primaryKey(),
            'id_team' => $this->integer()->notNull(),
            'username' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime(),
        ]);
        $this->addForeignKey('fk__fort_boyard_access__id_team', '{{%fort_boyard_access}}', 'id_team', '{{%fort_boyard_teams}}', 'id', 'cascade');

        $this->createTable('{{%fort_boyard}}', [
            'id' => $this->primaryKey(),
            'id_team' => $this->integer()->notNull(),
            'date_show_1' => $this->dateTime()->notNull(),
            'date_show_2' => $this->dateTime()->notNull(),
            'title' => $this->string(250)->notNull(),
            'text' => $this->text(),
            'date_create' => $this->dateTime(),
        ]);
        $this->addForeignKey('fk__fort_boyard__id_team', '{{%fort_boyard}}', 'id_team', '{{%fort_boyard_teams}}', 'id', 'cascade');

        $this->createTable('{{%fort_boyard_answers}}', [
            'id' => $this->primaryKey(),
            'id_fort_boyard' => $this->integer()->notNull(),
            'answer' => $this->string(1000)->notNull(),
            'is_right' => $this->boolean(),
            'username' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime(),
        ]);
        $this->addForeignKey('fk__fort_boyard_answers__id_fort_boyard', '{{%fort_boyard_answers}}', 'id_fort_boyard', '{{%fort_boyard}}', 'id', 'cascade');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk__fort_boyard_answers__id_fort_boyard', '{{%fort_boyard_answers}}');
        $this->dropTable('{{%fort_boyard_answers}}');

        $this->dropForeignKey('fk__fort_boyard__id_team', '{{%fort_boyard}}');
        $this->dropTable('{{%fort_boyard}}');

        $this->dropForeignKey('fk__fort_boyard_access__id_team', '{{%fort_boyard_access}}');
        $this->dropTable('{{%fort_boyard_access}}');

        $this->dropTable('{{%fort_boyard_teams}}');
    }

  
}
