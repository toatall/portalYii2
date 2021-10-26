<?php

use yii\db\Migration;

/**
 * Class m211026_042809_table_create_pay_taxes_visit
 */
class m211026_042809_table_create_pay_taxes_visit extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pay_taxes_visit}}', [
            'id' => $this->primaryKey(),
            'ip_address' => $this->string(50),
            'client_host' => $this->string(250),
            'username' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%pay_taxes_visit}}');
    }

  
}
