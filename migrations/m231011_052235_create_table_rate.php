<?php

use yii\db\Migration;

/**
 * Class m231011_052235_create_table_rate
 */
class m231011_052235_create_table_rate extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%automation_routine_rate}}', [
            'id' => $this->primaryKey(),
            'id_automation_routine' => $this->integer()->notNull(),
            'rate' => $this->smallInteger()->notNull(),
            'author' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime()->notNull(),
        ]);
        $this->addForeignKey('fk__automation_routine_rate__id_automation_route', '{{%automation_routine_rate}}', 'id_automation_routine',
            '{{%automation_routine}}', 'id', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk__automation_routine_rate__id_automation_route', '{{%automation_routine_rate}}');
        $this->dropTable('{{%automation_routine_rate}}');
    }
    
}
