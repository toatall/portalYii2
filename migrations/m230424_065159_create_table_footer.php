<?php

use yii\db\Migration;

/**
 * Class m230424_065159_create_table_footer
 */
class m230424_065159_create_table_footer extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%footer_type}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(500)->notNull(),
            'date_create' => $this->integer(),
        ]);

        $this->createTable('{{%footer_data}}', [
            'id' => $this->primaryKey(),
            'id_type' => $this->integer()->notNull(),            
            'url' => $this->string(500)->notNull(),
            'text' => $this->string(500)->notNull(),
            'target' => $this->string(50),
            'options' => $this->text(),
            'date_create' => $this->integer(),
        ]);
        $this->addForeignKey('fk__footer_data__id_type', '{{%footer_data}}', 'id_type', 
            '{{%footer_type}}', 'id', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk__footer_data__id_type', '{{%footer_data}}');
        $this->dropTable('{{%footer_data}}');

        $this->dropTable('{{%footer_type}}');
    }

    
}
