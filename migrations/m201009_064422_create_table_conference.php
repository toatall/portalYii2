<?php

use yii\db\Migration;

/**
 * Class m201009_064422_create_table_conference
 */
class m201009_064422_create_table_conference extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // table conference
        $this->createTable('{{%conference}}', [
            'id' => $this->primaryKey(),
            'type_conference' => $this->smallInteger()->notNull(),
            'theme' => $this->string(500)->notNull(),
            'responsible' => $this->string('max'),
            'members_people' => $this->string('max'),
            'members_organization' => $this->string('max'),
            'date_start' => $this->dateTime()->notNull(),
            'time_start_msk' => $this->boolean()->defaultValue(0)->notNull(),
            'duration' => $this->string(20),
            'is_confidential' => $this->boolean()->defaultValue(0)->notNull(),
            'place' => $this->string(100),
            'note' => $this->string('max'),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'date_edit' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'date_delete' => $this->dateTime(),
            'log_change' => $this->text(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drop conference
        $this->dropTable('{{%conference}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201009_064422_create_table_conference cannot be reverted.\n";

        return false;
    }
    */
}
