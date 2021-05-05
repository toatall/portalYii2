<?php

use yii\db\Migration;

/**
 * Class m201009_075232_create_table_email_goverment
 */
class m201009_075232_create_table_email_goverment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // table email_goverment
        $this->createTable('{{%email_goverment}}', [
            'id' => $this->primaryKey(),
            'org_name' => $this->string(1000)->notNull(),
            'ruk_name' => $this->string(1000),
            'telephone' => $this->string(200),
            'email' => $this->string(500)->notNull(),
            'post_address' => $this->string('max'),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'date_edit' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'author' => $this->string(250)->notNull(),
        ]);
        $this->addForeignKey('fk__email_goverment__author____user', '{{%email_goverment}}', 'author', '{{%user}}', 'username_windows');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drop email_goverment
        $this->dropForeignKey('fk__email_goverment__author____user', '{{%email_goverment}}');
        $this->dropTable('{{%email_goverment}}');
    }

}
