<?php

use yii\db\Migration;

/**
 * Class m230512_092506_create_table_grant_access
 */
class m230512_092506_create_table_grant_access extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%grant_access_group}}', [
            'id' => $this->primaryKey(),
            'unique' => $this->string(50)->notNull()->unique(),            
            'title' => $this->string(200)->notNull(),
            'note' => $this->text(),
            'date_create' => $this->integer(),
            'date_update' => $this->integer(),
            'author' => $this->string(250),
        ]);
        $this->addForeignKey('fk__grant_access_group__author', '{{%grant_access_group}}', 
            'author', '{{%user}}', 'username');

        $this->createTable('{{%grant_access_group__user}}', [
            'id_group' => $this->integer()->notNull(),
            'id_user' => $this->integer()->notNull(),
            'date_create' => $this->integer(),
        ]);
        $this->addForeignKey('fk__grant_access_group__user__id_group', '{{%grant_access_group__user}}', 
            'id_group', '{{%grant_access_group}}', 'id', 'cascade');
        $this->addForeignKey('fk__grant_access_group__user__id_user', '{{%grant_access_group__user}}', 
            'id_user', '{{%user}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk__grant_access_group__user__id_group', '{{%grant_access_group__user}}');
        $this->dropForeignKey('fk__grant_access_group__user__id_user', '{{%grant_access_group__user}}');
        $this->dropTable('{{%grant_access_group__user}}');

        $this->dropForeignKey('fk__grant_access_group__author', '{{%grant_access_group}}');
        $this->dropTable('{{%grant_access_group}}');
    }
    
}
