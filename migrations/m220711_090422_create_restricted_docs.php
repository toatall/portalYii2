<?php

use yii\db\Migration;

/**
 * Class m220711_090422_create_restricted_docs
 */
class m220711_090422_create_restricted_docs extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {        
        
        // The list of organizations 
        $this->createTable('{{%restricted_docs_orgs}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(500)->notNull()->unique(),
            'is_show_result' => $this->boolean(),
            'text_result' => $this->text(),
            'date_create' => $this->integer()->notNull(),
            'date_update' => $this->integer()->notNull(),
            'author' => $this->string(250)->notNull(),
        ]);
        $this->addForeignKey('fk__restricted_docs_orgs__author', '{{%restricted_docs_orgs}}', 
            'author', '{{%user}}', 'username');

        // The list of types documents
        $this->createTable('{{%restricted_docs_types}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(500)->notNull(),
            'date_create' => $this->integer()->notNull(),
            'date_update' => $this->integer()->notNull(),
            'author' => $this->string(250)->notNull(),
        ]);
        $this->addForeignKey('fk__restricted_docs_types__author', '{{%restricted_docs_types}}', 
            'author', '{{%user}}', 'username');

        // Restricted documents
        $this->createTable('{{%restricted_docs}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(1000)->notNull(),
            'doc_num' => $this->string(200),
            'doc_date' => $this->date(),
            'privacy_sign_desc' => $this->text(),
            'is_privacy' => $this->boolean(),
            'description_internet' => $this->string('max'),
            'owner' => $this->string(2000),
            'date_create' => $this->integer()->notNull(),
            'date_update' => $this->integer()->notNull(),
            'author' => $this->string(250)->notNull(),
        ]);
        $this->addForeignKey('fk__restricted_docs__author', '{{%restricted_docs}}', 
            'author', '{{%user}}', 'username');

        // Links many to many. List of organizations <=> restricted documents
        $this->createTable('{{%restricted_docs_orgs__restricted_docs}}', [
            'id_doc' => $this->integer()->notNull(),
            'id_org' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk__restricted_docs_orgs__restricted_docs__id_doc', '{{%restricted_docs_orgs__restricted_docs}}', 
            'id_doc', '{{%restricted_docs}}', 'id', 'cascade');
        $this->addForeignKey('fk__restricted_docs_orgs__restricted_docs__id_org', '{{%restricted_docs_orgs__restricted_docs}}', 
            'id_org', '{{%restricted_docs_orgs}}', 'id', 'cascade');

        // Links many to many. List of document types <=> restricted documents
        $this->createTable('{{%restricted_docs_types__restricted_docs}}', [
            'id_doc' => $this->integer()->notNull(),
            'id_type' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk__restricted_docs_types__restricted_docs__id_doc', '{{%restricted_docs_types__restricted_docs}}', 
            'id_doc', '{{%restricted_docs}}', 'id', 'cascade');
        $this->addForeignKey('fk__restricted_docs_types__restricted_docs__id_type', '{{%restricted_docs_types__restricted_docs}}', 
            'id_type', '{{%restricted_docs_types}}', 'id', 'cascade');        
            
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
                
        $this->dropTable('{{%restricted_docs_types__restricted_docs}}');
        
        $this->dropTable('{{%restricted_docs_orgs__restricted_docs}}');
        
        $this->dropTable('{{%restricted_docs_types}}');

        $this->dropTable('{{%restricted_docs_orgs}}');
        
        $this->dropTable('{{%restricted_docs}}');
    }

    
}
