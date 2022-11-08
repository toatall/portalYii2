<?php

use yii\db\Migration;

/**
 * Class m221108_070202_table_migrants_questionnation
 */
class m221108_070202_table_migrants_questionnation extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%migrants_questionnation}}', [
            'id' => $this->primaryKey(),
            'ul_name' => $this->string(500)->notNull(),
            'ul_inn' => $this->string(12)->notNull(),
            'ul_kpp' => $this->string(10),
            'date_send_notice' => $this->date()->notNull(),
            'region_migrate' => $this->string(200)->notNull(),
            'cause_migrate' => $this->string('max')->notNull(),
            'date_create' => $this->integer()->notNull(),
            'date_update' => $this->integer()->notNull(),
            'author' => $this->string(250),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%migrants_questionnation}}');
    }
    
}
