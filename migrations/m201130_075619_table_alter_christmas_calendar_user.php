<?php

use yii\db\Migration;

/**
 * Class m201130_075619_table_alter_christmas_calendar_user
 */
class m201130_075619_table_alter_christmas_calendar_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%christmas_calendar_users}}', 'department', $this->string(250));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%christmas_calendar_users}}', 'department');
    }
}
