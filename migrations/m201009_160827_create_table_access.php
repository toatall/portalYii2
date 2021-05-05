<?php

use yii\db\Migration;

/**
 * Class m201009_160827_create_table_access
 */
class m201009_160827_create_table_access extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // table access_group
        $this->createTable('{{%access_group}}', [
            'id' => $this->primaryKey(),
            'id_tree' => $this->integer()->notNull(),
            'id_group' => $this->integer()->notNull(),
            'id_organization' => $this->string(5)->notNull(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
        ]);
        $this->addForeignKey('fk__access_group__id_tree____tree', '{{%access_group}}', 'id_tree',
            '{{%tree}}', 'id', 'cascade');
        $this->addForeignKey('fk__access_group__id_group____group', '{{%access_group}}', 'id_group',
            '{{%group}}', 'id', 'cascade');
        $this->addForeignKey('fk__access_group__id_organization____organization', '{{%access_group}}', 'id_organization',
            '{{%organization}}', 'code');

        // table access_user
        $this->createTable('{{%access_user}}', [
            'id' => $this->primaryKey(),
            'id_tree' => $this->integer()->notNull(),
            'id_user' => $this->integer()->notNull(),
            'id_organization' => $this->string(5)->notNull(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
        ]);
        $this->addForeignKey('fk__access_user__id_tree____tree', '{{%access_user}}', 'id_tree',
            '{{%tree}}', 'id', 'cascade');
        $this->addForeignKey('fk__access_user__id_user____user', '{{%access_user}}', 'id_user',
            '{{%user}}', 'id', 'cascade');
        $this->addForeignKey('fk__access_user__id_organization____organization', '{{%access_user}}', 'id_organization',
            '{{%organization}}', 'code');


        // table access_department_group
        $this->createTable('{{%access_department_group}}', [
            'id' => $this->primaryKey(),
            'id_department' => $this->integer()->notNull(),
            'id_group' => $this->integer()->notNull(),
            'id_organization' => $this->string(5)->notNull(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
        ]);
        $this->addForeignKey('fk__access_department_group__id_department____department', '{{%access_department_group}}', 'id_department',
            '{{%department}}', 'id', 'cascade');
        $this->addForeignKey('fk__access_department_group__id_group____group', '{{%access_department_group}}', 'id_group',
            '{{%group}}', 'id', 'cascade');
        $this->addForeignKey('fk__access_department_group__id_organization____organization', '{{%access_department_group}}', 'id_organization',
            '{{%organization}}', 'code');

        // table access_department_user
        $this->createTable('{{%access_department_user}}', [
            'id' => $this->primaryKey(),
            'id_department' => $this->integer()->notNull(),
            'id_user' => $this->integer()->notNull(),
            'id_organization' => $this->string(5)->notNull(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
        ]);
        $this->addForeignKey('fk__access_department_user__id_department____department', '{{%access_department_user}}', 'id_department',
            '{{%department}}', 'id', 'cascade');
        $this->addForeignKey('fk__access_department_user__id_user____user', '{{%access_department_user}}', 'id_user',
            '{{%user}}', 'id', 'cascade');
        $this->addForeignKey('fk__access_department_user__id_organization____organization', '{{%access_department_user}}', 'id_organization',
            '{{%organization}}', 'code');


        // table access_organization_group
        $this->createTable('{{%access_organization_group}}', [
            'id' => $this->primaryKey(),
            'id_access_group' => $this->integer()->notNull(),
            'id_organization' => $this->string(5)->notNull(),
            'author' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
        ]);
        $this->addForeignKey('fk__access_organization_group__id_access_group____group', '{{%access_organization_group}}', 'id_access_group',
            '{{%group}}', 'id', 'cascade');
        $this->addForeignKey('fk__access_organization_group__author____user', '{{%access_organization_group}}', 'author',
            '{{%user}}', 'username_windows', 'cascade');
        $this->addForeignKey('fk__access_organization_group__id_organization____organization', '{{%access_organization_group}}', 'id_organization',
            '{{%organization}}', 'code');

        // table access_organization_user
        $this->createTable('{{%access_organization_user}}', [
            'id' => $this->primaryKey(),
            'id_access_user' => $this->integer()->notNull(),
            'id_organization' => $this->string(5)->notNull(),
            'author' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
        ]);
        $this->addForeignKey('fk__access_organization_user__id_access_user____user', '{{%access_organization_user}}', 'id_access_user',
            '{{%user}}', 'id', 'cascade');
        $this->addForeignKey('fk__access_organization_user__author____user', '{{%access_organization_user}}', 'author',
            '{{%user}}', 'username_windows');
        $this->addForeignKey('fk__access_organization_user__id_organization____organization', '{{%access_organization_user}}', 'id_organization',
            '{{%organization}}', 'code');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drop access_organization_user
        $this->dropForeignKey('fk__access_organization_user__id_organization____organization', '{{%access_organization_user}}');
        $this->dropForeignKey('fk__access_organization_user__author____user', '{{%access_organization_user}}');
        $this->dropForeignKey('fk__access_organization_user__id_access_user____user', '{{%access_organization_user}}');
        $this->dropTable('{{%access_organization_user}}');

        // drop access_organization_group
        $this->dropForeignKey('fk__access_organization_group__id_access_group____group', '{{%access_organization_group}}');
        $this->dropForeignKey('fk__access_organization_group__author____user', '{{%access_organization_group}}');
        $this->dropForeignKey('fk__access_organization_group__id_organization____organization', '{{%access_organization_group}}');
        $this->dropTable('{{%access_organization_group}}');


        // drop access_department_user
        $this->dropForeignKey('fk__access_department_user__id_department____department', '{{%access_department_user}}');
        $this->dropForeignKey('fk__access_department_user__id_user____user', '{{%access_department_user}}');
        $this->dropForeignKey('fk__access_department_user__id_organization____organization', '{{%access_department_user}}');
        $this->dropTable('{{%access_department_user}}');

        // drop access_department_group
        $this->dropForeignKey('fk__access_department_group__id_department____department', '{{%access_department_group}}');
        $this->dropForeignKey('fk__access_department_group__id_group____group', '{{%access_department_group}}');
        $this->dropForeignKey('fk__access_department_group__id_organization____organization', '{{%access_department_group}}');
        $this->dropTable('{{%access_department_group}}');


        // drop access_user
        $this->dropForeignKey('fk__access_user__id_tree____tree', '{{%access_user}}');
        $this->dropForeignKey('fk__access_user__id_user____user', '{{%access_user}}');
        $this->dropForeignKey('fk__access_user__id_organization____organization', '{{%access_user}}');
        $this->dropTable('{{%access_user}}');

        // drop access_group
        $this->dropForeignKey('fk__access_group__id_tree____tree', '{{%access_group}}');
        $this->dropForeignKey('fk__access_group__id_group____group', '{{%access_group}}');
        $this->dropForeignKey('fk__access_group__id_organization____organization', '{{%access_group}}');
        $this->dropTable('{{%access_group}}');

    }


}
