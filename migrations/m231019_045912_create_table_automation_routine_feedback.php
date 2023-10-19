<?php

use yii\db\Migration;

/**
 * Class m231019_045912_create_table_automation_routine_feedback
 */
class m231019_045912_create_table_automation_routine_feedback extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%automation_routine_feedback}}', [
            'id' => $this->primaryKey(),
            'id_automation_routine' => $this->integer()->notNull(),
            'result' => $this->string(30)->notNull(),
            'author' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime()->notNull(),
        ]);
        $this->addForeignKey('fk__automation_routine_feedback__id_automation_route', '{{%automation_routine_feedback}}', 'id_automation_routine',
            '{{%automation_routine}}', 'id', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk__automation_routine_feedback__id_automation_route', '{{%automation_routine_feedback}}');
        $this->dropTable('{{%automation_routine_feedback}}');
    }

}
