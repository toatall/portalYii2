<?php

use yii\db\Migration;

/**
 * Class m200509_061513_create_general_tables
 */
class m200509_061513_create_general_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // table organization
        $this->createTable('{{%organization}}', [
            'code' => $this->string(5)->notNull(),
            'name' => $this->string(250)->notNull(),
            'sort' => $this->integer()->defaultValue(0),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'date_edit' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
        ]);
        $this->addPrimaryKey('pk__organization__code', '{{%organization}}', 'code');
        
        // table user
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(250)->unique()->notNull(),
            'password' => $this->string(150),
            'username_windows' => $this->string(250)->unique()->notNull(),            
            'fio' => $this->string(250),
            'default_organization' => $this->string(5),
            'current_organization' => $this->string(5),            
            'blocked' => $this->boolean()->defaultValue(0)->notNull(),          
            'telephone' => $this->string(50),
            'post' => $this->string(250),
            'rank' => $this->string(250),
            'about' => $this->text(),
            'department' => $this->string(250),
            'hash' => $this->string(32),
            'organization_name' => $this->string(250),
            'last_login' => $this->dateTime(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'date_edit' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'date_delete' => $this->dateTime(),
        ]);
        
        // table user_organization
        $this->createTable('{{%user_organization}}', [
            'id_user' => $this->integer()->notNull(),
            'id_organization' => $this->string(5)->notNull(),
        ]);
        $this->addForeignKey('fk__user_organization__id_user____user', '{{%user_organization}}', 'id_user', '{{%user}}', 'id', 'cascade');
        $this->addForeignKey('fk__user_organization__id_organization____organization', '{{%user_organization}}', 'id_organization', '{{%organization}}', 'code', 'cascade');

        // table tree
        $this->createTable('{{%tree}}', [
            'id' => $this->primaryKey(),
            'id_parent' => $this->integer()->defaultValue(0)->notNull(),
            'id_organization' => $this->string(5)->notNull(),
            'name' => $this->string(250)->notNull(),
            'module' => $this->string(50),
            'use_organization' => $this->boolean()->defaultValue(0),
            'use_material' => $this->boolean()->defaultValue(0),
            'use_tape' => $this->boolean()->defaultValue(0),
            'sort' => $this->integer()->defaultValue(0),
            'author' => $this->string(250)->notNull(),
            'log_change' => $this->text(),
            'param1' => $this->string(100),
            'disable_child' => $this->boolean()->defaultValue(0),
            'alias' => $this->string(50),
            'view_static' => $this->string(50),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'date_edit' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'date_delete' => $this->dateTime(),
        ]);
        $this->addForeignKey('fk__tree__id_organization____organization', '{{%tree}}', 'id_organization', '{{%organization}}', 'code');
        $this->addForeignKey('fk__tree__author____user', '{{%tree}}', 'author', '{{%user}}', 'username_windows');

        // table module
        $this->createTable('{{%module}}', [
            'name' => $this->string(50)->notNull(),
            'description' => $this->string(250)->notNull(),
            'only_one' => $this->boolean()->defaultValue(0)->notNull(),            
            'class_namespace' => $this->string(150),
            'log_change' => $this->text(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'author' => $this->string(250)->notNull(),
        ]);
        $this->addPrimaryKey('pk__module__name', '{{%module}}', 'name');
        $this->addForeignKey('fk__module__author____user', '{{%module}}', 'author', '{{%user}}', 'username_windows');
        
        // table menu
        $this->createTable('{{%menu}}', [
            'id' => $this->primaryKey(),
            'id_parent' => $this->integer()->notNull(),
            'type_menu' => $this->smallInteger()->notNull(),
            'name' => $this->string(100)->notNull(),
            'link' => $this->string(500),
            'submenu_code' => $this->text(),
            'target' => $this->string(10),
            'blocked' => $this->boolean()->defaultValue(0)->notNull(),
            'sort_index' => $this->integer()->defaultValue(0)->notNull(),
            'key_name' => $this->string(50),
            'author' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'date_edit' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'log_change' => $this->text(),
        ]);       
        $this->addForeignKey('fk__menu__author____user', '{{%menu}}', 'author', '{{%user}}', 'username_windows');

        // table file
        $this->createTable('{{%file}}', [
            'id' => $this->primaryKey(),
            'id_organization' => $this->string(5)->null(),
            'id_model' => $this->integer()->notNull(),
            'model' => $this->string(50)->notNull(),
            'file_name' => $this->string(250)->notNull(),
            'full_filename' => $this->string(500),
            'file_size' => $this->integer(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'count_download' => $this->integer()->defaultValue(0),
            'author' => $this->string(250)->notNull(),
        ]);
        $this->addForeignKey('fk__file__author____user', '{{%file}}', 'author', '{{%user}}', 'username_windows');
        $this->addForeignKey('fk__file__id_organization____organization', '{{%file}}', 'id_organization', '{{%organization}}', 'code');
        
        // table file_download
        $this->createTable('{{%file_download}}', [
            'id' => $this->primaryKey(),
            'id_file' => $this->string(5)->null(),
            'username' => $this->string(250)->notNull(),            
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'session_id' => $this->string(32)->defaultValue(0),            
        ]);
        $this->addForeignKey('fk__file_download__username____user', '{{%file_download}}', 'username', '{{%user}}', 'username_windows');
        
        // table image
        $this->createTable('{{%image}}', [
            'id' => $this->primaryKey(),
            'id_model' => $this->integer()->notNull(),
            'model' => $this->string(50)->notNull(),
            'image_name' => $this->string(250)->notNull(),
            'image_name_thumbs' => $this->string(250),
            'image_size' => $this->integer(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),            
        ]); 
        
        // table group
        $this->createTable('{{%group}}', [
            'id' => $this->primaryKey(),
            'id_organization' => $this->string(5)->notNull(),
            'name' => $this->string(250)->notNull()->unique(),
            'description' => $this->text(),
            'sort' => $this->integer()->defaultValue(0)->notNull(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'date_edit' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
        ]);
        $this->addForeignKey('fk__group__id_organization____organization', '{{%group}}', 'id_organization', '{{%organization}}', 'code');
        
        // table group_user
        $this->createTable('{{%group_user}}', [            
            'id_group' => $this->integer()->notNull(),
            'id_user' => $this->integer()->notNull(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
        ]);
        $this->addForeignKey('fk__group_user__id_group____group', '{{%group_user}}', 'id_group', '{{%group}}', 'id', 'cascade');
        $this->addForeignKey('fk__group_user__id_user____user', '{{%group_user}}', 'id_user', '{{%user}}', 'id', 'cascade');

        // table log
        $this->createTable('{{%log}}', [
            'id' => $this->primaryKey(),
            'id_user' => $this->integer(),
            'username' => $this->string(250)->notNull(),
            'model_name' => $this->string(250)->notNull(),
            'id_model' => $this->integer()->notNull(),
            'operation' => $this->smallInteger()->notNull(),
            'remote_ip' => $this->string(50),
            'remote_host' => $this->string(50),
            'is_delete' => $this->boolean()->notNull(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
        ]);

        // table log_authenticate
        $this->createTable('{{%log_authenticate}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(250)->notNull(),
            'operation' => $this->string(50)->notNull(),
            'session_id' => $this->string(32),
            'remote_ip_address' => $this->string(15),
            'remote_host_name' => $this->string(50),    
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'browser_name' => $this->string(30),  
            'browser_version' => $this->string(20), 
            'client_platform' => $this->string(30), 
            'agent_str' => $this->string(500), 
            'last_action' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
        ]);
        $this->addForeignKey('fk__log_authenticate__username____user', '{{%log_authenticate}}', 'username', '{{%user}}', 'username_windows');
        
        // table telephone
        $this->createTable('{{%telephone}}', [
            'id' => $this->primaryKey(),
            'id_tree' => $this->integer()->notNull(),
            'id_organization' => $this->string(5)->notNull(),
            'telephone_file' => $this->string(250)->notNull(),
            'dop_text' => $this->string(250),
            'sort' => $this->integer()->defaultValue(0),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'date_edit' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'author' => $this->string(250)->notNull(),
            'log_change' => $this->text(),
            'count_download' => $this->integer()->defaultValue(0),                       
        ]);
        $this->addForeignKey('fk__telephone__id_tree____tree', '{{%telephone}}', 'id_tree', '{{%tree}}', 'id');
        $this->addForeignKey('fk__telephone__id_organization____organization', '{{%telephone}}', 'id_organization', '{{%organization}}', 'code');
        $this->addForeignKey('fk__telephone__author____user', '{{%telephone}}', 'author', '{{%user}}', 'username_windows');

        // table telephone_download
        $this->createTable('{{%telephone_download}}', [
            'id' => $this->primaryKey(),
            'id_telephone' => $this->integer()->notNull(),
            'username' => $this->string(250)->notNull(),
            'ip_address' => $this->string(20),
            'hostname' => $this->string(100),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
        ]);
        $this->addForeignKey('fk__telephone_download__id_telephone____telephone', '{{%telephone_download}}', 'id_telephone', '{{%telephone}}', 'id');
        $this->addForeignKey('fk__telephone_download__username____user', '{{%telephone_download}}', 'username', '{{%user}}', 'username_windows');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drop telephone_download
        $this->dropForeignKey('fk__telephone_download__id_telephone____telephone', '{{%telephone_download}}');
        $this->dropForeignKey('fk__telephone_download__username____user', '{{%telephone_download}}');
        $this->dropTable('{{%telephone_download}}');

        // drop telephone
        $this->dropForeignKey('fk__telephone__id_tree____tree', '{{%telephone}}');
        $this->dropForeignKey('fk__telephone__id_organization____organization', '{{%telephone}}');
        $this->dropForeignKey('fk__telephone__author____user', '{{%telephone}}');
        $this->dropTable('{{%telephone}}');

        // drop log_authenticate
        $this->dropForeignKey('fk__log_authenticate__username____user', '{{%log_authenticate}}');
        $this->dropTable('{{%log_authenticate}}');

        // drop log
        $this->dropTable('{{%log}}');

        // drop group_user
        $this->dropForeignKey('fk__group_user__id_group____group', '{{%group_user}}');
        $this->dropForeignKey('fk__group_user__id_user____user', '{{%group_user}}');
        $this->dropTable('{{%group_user}}');
        
        // drop group
        $this->dropForeignKey('fk__group__id_organization____organization', '{{%group}}');
        $this->dropTable('{{%group}}');
        
        // drop image
        $this->dropTable('{{%image}}');
        
        // drop file_download
        $this->dropForeignKey('fk__file_download__username____user', '{{%file_download}}');
        $this->dropTable('{{%file_download}}');
        
        // drop file
        $this->dropForeignKey('fk__file__author____user', '{{%file}}');
        $this->dropForeignKey('fk__file__id_organization____organization', '{{%file}}');
        $this->dropTable('{{%file}}');

        // drop menu
        $this->dropForeignKey('fk__menu__author____user', '{{%menu}}');
        $this->dropTable('{{%menu}}');
        
        // drop module
        $this->dropPrimaryKey('pk__module__name', '{{%module}}');
        $this->dropForeignKey('fk__module__author____user', '{{%module}}');
        $this->dropTable('{{%module}}');
        
        // drop table tree
        $this->dropForeignKey('fk__tree__id_organization____organization', '{{%tree}}');
        $this->dropForeignKey('fk__tree__author____user', '{{%tree}}');
        $this->dropTable('{{%tree}}');
        
        // drop table user_organization
        $this->dropForeignKey('fk__user_organization__id_user____user', '{{%user_organization}}');
        $this->dropForeignKey('fk__user_organization__id_organization____organization', '{{%user_organization}}');
        $this->dropTable('{{%user_organization}}');
        
        // drop table user
        $this->dropTable('{{%user}}');
        
        // dtop table organization
        $this->dropPrimaryKey('pk__organization__code', '{{%organization}}');
        $this->dropTable('{{%organization}}');                
    }

}
