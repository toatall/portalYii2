<?php

use yii\db\Migration;

/**
 * Class m220525_081540_create_table_contest_quest
 */
class m220525_081540_create_table_contest_quest extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%contest_quest}}', [
            'id' => $this->primaryKey(),
            'step' => $this->smallInteger()->notNull(),
            'balls' => $this->smallInteger()->notNull(),
            'data' => $this->string('max'),
            'username' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%contest_quest}}');
    }
    
}
