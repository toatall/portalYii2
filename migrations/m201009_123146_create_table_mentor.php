<?php

use yii\db\Migration;

/**
 * Class m201009_123146_create_table_mentor
 */
class m201009_123146_create_table_mentor extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // table mentor_ways
        $this->createTable('{{%mentor_ways}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(200)->notNull(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'date_update' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
        ]);

        // table mentor_role_assign
        $this->createTable('{{%mentor_role_assign}}', [
            'id' => $this->primaryKey(),
            'role_name' => $this->string(100)->notNull(),
            'username' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
        ]);
        $this->addForeignKey('fk__mentor_role_assign__username____user', '{{%mentor_role_assign}}',
            'username', '{{%user}}', 'username_windows');

        // table mentor_post
        $this->createTable('{{%mentor_post}}', [
            'id' => $this->primaryKey(),
            'id_mentor_ways' => $this->integer()->notNull(),
            'id_organization' => $this->integer()->notNull(),
            'title' => $this->string(500)->notNull(),
            'message1' => $this->string('max')->notNull(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'date_update' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'date_delete' => $this->dateTime(),
            'author' => $this->string(250)->notNull(),
            'count_like' => $this->integer()->defaultValue(0),
            'count_comment' => $this->integer()->defaultValue(0),
            'count_visit' => $this->integer()->defaultValue(0),
        ]);
        $this->addForeignKey('fk__mentor_post__id_mentor_ways____mentor_ways', '{{%mentor_post}}',
        'id_mentor_ways', '{{%mentor_ways}}', 'id', 'cascade');
        $this->addForeignKey('fk__mentor_post__author____user', '{{%mentor_post}}',
        'author', '{{%user}}', 'username_windows');

        // mentor_post_files
        $this->createTable('{{%mentor_post_files}}', [
            'id' => $this->primaryKey(),
            'id_mentor_post' => $this->integer()->notNull(),
            'filename' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
        ]);
        $this->addForeignKey('fk__mentor_post_files__id_mentor_post____mentor_post', '{{%mentor_post_files}}',
        'id_mentor_post', '{{%mentor_post}}', 'id', 'cascade');

        // mentor_post_like
        $this->createTable('{{%mentor_post_like}}', [
            'id' => $this->primaryKey(),
            'id_mentor_post' => $this->integer()->notNull(),
            'username' => $this->string(250)->notNull(),
            'ip_address' => $this->string(50),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
        ]);
        $this->addForeignKey('fk__mentor_post_like__id_mentor_post____mentor_post', '{{%mentor_post_like}}',
        'id_mentor_post', '{{%mentor_post}}', 'id', 'cascade');
        $this->addForeignKey('fk__mentor_post_like__username____user', '{{%mentor_post_like}}',
            'username', '{{%user}}', 'username_windows');

        // mentor_post_visit
        $this->createTable('{{%mentor_post_visit}}', [
            'id' => $this->primaryKey(),
            'id_mentor_post' => $this->integer()->notNull(),
            'username' => $this->string(250)->notNull(),
            'ip_address' => $this->string(50),
            'hostname' => $this->string(50),
            'session_id' => $this->string(100),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
        ]);
        $this->addForeignKey('fk__mentor_post_visit__id_mentor_post____mentor_post', '{{%mentor_post_visit}}',
        'id_mentor_post', '{{%mentor_post}}', 'id', 'cascade');
        $this->addForeignKey('fk__mentor_post_visit__username____user', '{{%mentor_post_visit}}',
        'username', '{{%user}}', 'username_windows');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drop mentor_visit
        $this->dropForeignKey('fk__mentor_post_visit__id_mentor_post____mentor_post', '{{%mentor_post_visit}}');
        $this->dropForeignKey('fk__mentor_post_visit__username____user', '{{%mentor_post_visit}}');
        $this->dropTable('{{%mentor_post_visit}}');

        // drop mentor_post_like
        $this->dropForeignKey('fk__mentor_post_like__id_mentor_post____mentor_post', '{{%mentor_post_like}}');
        $this->dropForeignKey('fk__mentor_post_like__username____user', '{{%mentor_post_like}}');
        $this->dropTable('{{%mentor_post_like}}');

        // drop mentor_post_files
        $this->dropForeignKey('fk__mentor_post_files__id_mentor_post____mentor_post', '{{%mentor_post_files}}');
        $this->dropTable('{{%mentor_post_files}}');

        // drop mentor_post
        $this->dropForeignKey('fk__mentor_post__id_mentor_ways____mentor_ways', '{{%mentor_post}}');
        $this->dropForeignKey('fk__mentor_post__author____user', '{{%mentor_post}}');
        $this->dropTable('{{%mentor_post}}');

        // drop mentor_role_assign
        $this->dropForeignKey('fk__mentor_role_assign__username____user', '{{%mentor_role_assign}}');
        $this->dropTable('{{%mentor_role_assign}}');

        // drop mentor_ways
        $this->dropTable('{{%mentor_ways}}');


    }

}

