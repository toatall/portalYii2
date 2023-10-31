<?php

use yii\db\Migration;

/**
 * Class m231027_063153_create_table_dash_ecr
 */
class m231027_063153_create_table_dash_ecr extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%dictionary_regions}}', [
            'reg_code' => $this->string(2)->notNull()->unique(),
            'reg_name' => $this->string(500)->notNull(),            
        ]);
        $this->createIndex('reg_code_dictionary_regions__index', '{{%dictionary_regions}}', 'reg_code');
        
        $this->createTable('{{%migrate_regions}}', [
            'id' => $this->primaryKey(),
            'reg_code' => $this->string(2)->notNull(),
            'count_in' => $this->decimal()->defaultValue(0),
            'count_out' => $this->decimal()->defaultValue(0),
            'date_create' => $this->dateTime()->notNull(),
            'date_update' => $this->dateTime(),
        ]);
        $this->addForeignKey('fk__migrate_regions__reg_code', '{{%migrate_regions}}', 'reg_code', '{{%dictionary_regions}}', 'reg_code');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk__migrate_regions__reg_code', '{{%migrate_regions}}');
        $this->dropTable('{{%migrate_regions}}');

        $this->dropTable('{{%dictionary_regions}}');
    }
    
}
