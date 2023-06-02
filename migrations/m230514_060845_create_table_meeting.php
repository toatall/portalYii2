<?php

use yii\db\Migration;

/**
 * Class m230514_060845_create_table_meeting
 */
class m230514_060845_create_table_meeting extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%meeting}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string(30)->notNull(),
            'org_code' => $this->string(5)->notNull(),
            'theme' => $this->string(500)->notNull(),
            'date_start' => $this->integer()->notNull(),
            'duration' => $this->integer(),
            'place' => $this->string(100), 
            'note' => $this->text(),
            'members_people' => $this->text(),
            'members_organization' => $this->text(),
            'responsible' => $this->text(),
            'date_create' => $this->integer()->notNull(),
            'date_update' => $this->integer()->notNull(),
            'date_delete' => $this->integer(),
            'author' => $this->string(250)->notNull(),
        ]);
        $this->addForeignKey('fk__meeting__author', '{{%meeting}}', 
            'author', '{{%user}}', 'username');


        // addations for class VksExternal
        $this->createTable('{{%meeting_vks_external}}', [
            'id' => $this->primaryKey(),
            'id_meeting' => $this->integer()->notNull(),            
            'format_holding' => $this->string(50)->notNull(),
            'person_head' => $this->string(500)->notNull(),
            'members_count' => $this->smallInteger()->notNull(),
            'members_count_ufns' => $this->smallInteger()->notNull(),
            'material_translation' => $this->string(250)->notNull(),
            'link_event' => $this->string(500),
            'is_connect_vks_fns' => $this->boolean(),
            'platform' => $this->string(250),
            'full_name_support_ufns' => $this->string(500),
            'date_test_vks' => $this->integer(),
            'count_notebooks' => $this->smallInteger(),        
        ]);
        $this->addForeignKey('fk__meeting_vks_external__id_meeting', '{{%meeting_vks_external}}',
            'id_meeting', '{{%meeting}}', 'id', 'cascade');

        $this->createTable('{{%meeting_locations}}', [
            'id' => $this->primaryKey(),
            'location' => $this->string(200)->notNull()->unique(),
            'date_create' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%meeting_locations}}');

        $this->dropForeignKey('fk__meeting_vks_external__id_meeting', '{{%meeting_vks_external}}');
        $this->dropTable('{{%meeting_vks_external}}');

        $this->dropForeignKey('fk__meeting__author', '{{%meeting}}');
        $this->dropTable('{{%meeting}}');
    }

    
}
