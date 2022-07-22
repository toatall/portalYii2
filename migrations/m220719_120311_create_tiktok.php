<?php

use yii\db\Migration;

/**
 * Class m220719_120311_create_tiktok
 */
class m220719_120311_create_tiktok extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tiktok}}', [
            'id' => $this->primaryKey(),
            'department_id' => $this->integer()->notNull(),
            'description' => $this->text(),
            'filename' => $this->string(1000)->notNull(),
            'rate_1' => $this->smallInteger()->notNull(),
            'rate_2' => $this->smallInteger()->notNull(),
            'rate_3' => $this->smallInteger()->notNull(),
            'date_create' => $this->integer()->notNull(),
            'date_update' => $this->integer()->notNull(),
            'author' => $this->string(250)->notNull(),
        ]);

        $this->createTable('{{%tiktok_vote}}', [
            'id' => $this->primaryKey(),        
            'id_tiktok' => $this->integer()->notNull(),
            'rate_1' => $this->smallInteger()->notNull(),
            'rate_2' => $this->smallInteger()->notNull(),
            'rate_3' => $this->smallInteger()->notNull(),
            'date_create' => $this->integer()->notNull(),
            'author' => $this->string(250)->notNull(),
        ]);
        $this->addForeignKey('fk__tiktok_vote__id_tiktok', '{{%tiktok_vote}}', 'id_tiktok', '{{%tiktok}}', 'id', 'cascade');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tiktok_vote}}');
        $this->dropTable('{{%tiktok}}');
    }

    
}
