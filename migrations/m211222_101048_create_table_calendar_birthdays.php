<?php

use yii\db\Migration;

/**
 * Class m211222_101048_create_table_calendar_birthdays
 */
class m211222_101048_create_table_calendar_birthdays extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%calendar_bithdays}}', [
            'id' => $this->primaryKey(),
            'date' => $this->date()->notNull(),
            'fio' => $this->string(1000)->notNull(),
            'department' => $this->string(),
            'date_crate' => $this->dateTime(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%calendar_bithdays}}');
    }

}
