<?php

use yii\db\Migration;

/**
 * Class m201215_050848_table_create_compliments
 */
class m201215_050848_table_create_compliments extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%compliments_like}}', [
            'file_name' => $this->string(500)->notNull(),
            'username' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%compliments_like}}');
    }
}
