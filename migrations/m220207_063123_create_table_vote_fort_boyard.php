<?php

use yii\db\Migration;

/**
 * Class m220207_063123_create_table_vote_fort_boyard
 */
class m220207_063123_create_table_vote_fort_boyard extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%fort_boyard_team_vote}}', [
            'id' => $this->primaryKey(),
            'id_team' => $this->integer()->notNull(),
            'username' => $this->string(250)->notNull(),
            'rating_trial' => $this->smallInteger()->notNull(),
            'rating_name' => $this->smallInteger()->notNull(),
            'date_create' => $this->dateTime()->notNull(),
        ]);
        $this->addForeignKey('fk__fort_boyard_team_vote__id_team', '{{%fort_boyard_team_vote}}', 'id_team', '{{%fort_boyard_teams}}', 'id', 'cascade');
        $this->addForeignKey('fk__fort_boyard_team_vote__username', '{{%fort_boyard_team_vote}}', 'username', '{{%user}}', 'username');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk__fort_boyard_team_vote__username', '{{%fort_boyard_team_vote}}');
        $this->dropForeignKey('fk__fort_boyard_team_vote__id_team', '{{%fort_boyard_team_vote}}');
        $this->dropTable('{{%fort_boyard_team_vote}}');
    }

}
