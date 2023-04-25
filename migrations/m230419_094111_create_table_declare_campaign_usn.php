<?php

use yii\db\Migration;

/**
 * Class m230419_094111_create_table_declare_campaign_usn
 */
class m230419_094111_create_table_declare_campaign_usn extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%declare_campaign_usn}}', [
            'id' => $this->primaryKey(),
            'year' => $this->string(4)->notNull(),
            'date' => $this->date()->notNull(),
            'org_code' => $this->string(5)->notNull(),
            'count_np' => $this->integer()->notNull(),
            'count_np_ul' => $this->integer()->notNull(),
            'count_np_ip' => $this->integer()->notNull(),
            'count_np_provides_reliabe_declare' => $this->integer()->notNull(),
            'count_np_provides_not_required' => $this->integer()->notNull(),
            'date_create' => $this->integer(),
            'author' => $this->string(250),
        ]);
        $this->addForeignKey('fk__declare_campaign_usn__author', '{{%declare_campaign_usn}}', 
            'author', '{{%user}}', 'username');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk__declare_campaign_usn__author', '{{%declare_campaign_usn}}');
        $this->dropTable('{{%declare_campaign_usn}}');
    }
    
}
