<?php

use yii\db\Migration;

/**
 * Class m230929_041455_create_tables_contest_pets
 */
class m230929_041455_create_tables_contest_pets extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%contest_pets}}', [
            'id' => $this->primaryKey(),
            'pet_name' => $this->string(500)->notNull(),
            'pet_owner' => $this->string(250)->notNull(),            
            'pet_note' => $this->text(),
            'date_create' => $this->integer()->notNull(),            
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%contest_pets}}');
    }
    
}
