<?php

use yii\db\Migration;

/**
 * Class m210418_050817_create_table_contest_arts_vote
 */
class m210418_050817_create_table_contest_arts_vote extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%contest_arts_vote}}', [
            'id' => $this->primaryKey(),
            'id_contest_arts' => $this->integer()->notNull(),            
            'author' => $this->string(250)->notNull(),
            'rating_real_art' => $this->smallInteger(),
            'rating_original_name' => $this->smallInteger(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()'),
        ]);
        $this->addForeignKey('fk-contest_atrs_vote-id_contest_arts', '{{%contest_arts_vote}}', 'id_contest_arts', '{{%contest_arts}}', 'id', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-contest_atrs_vote-id_contest_arts', '{{%contest_arts_vote}}');
        $this->dropTable('{{%contest_arts_vote}}');
    }
    
}
