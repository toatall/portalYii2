<?php

use yii\db\Migration;

/**
 * Class m201009_064230_create_table_news
 */
class m201009_064230_create_table_news extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // table news
        $this->createTable('{{%news}}', [
            'id' => $this->primaryKey(),
            'id_tree' => $this->integer()->notNull(),
            'id_organization' => $this->string(5)->notNull(),
            'title' => $this->string(500)->notNull(),
            'message1' => $this->string('max'),
            'message2' => $this->string('max')->notNull(),
            'author' => $this->string(250)->notNull(),
            'general_page' => $this->boolean()->defaultValue(0)->notNull(),
            'date_start_pub' => $this->date()->notNull(),
            'date_end_pub' => $this->date()->notNull(),
            'flag_enable' => $this->boolean()->defaultValue(1)->notNull(),
            'thumbail_title' => $this->string(),
            'thumbail_image' => $this->string(),
            'thumbail_text' => $this->string(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'date_edit' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'date_delete' => $this->dateTime(),
            'log_change' => $this->text(),
            'on_general_page' => $this->boolean()->defaultValue(0),
            'count_like' => $this->integer()->defaultValue(0),
            'count_comment' => $this->integer()->defaultValue(0),
            'count_visit' => $this->integer()->defaultValue(0),
            'tags' => $this->string(1000),
            'date_sort' => $this->dateTime()->notNull(),
        ]);
        $this->addForeignKey('fk__news__id_tree____tree', '{{%news}}', 'id_tree', '{{%tree}}', 'id');
        $this->addForeignKey('fk__news__id_organization____organization', '{{%news}}', 'id_organization', '{{%organization}}', 'code');
        $this->addForeignKey('fk__news__author____user', '{{%news}}', 'author', '{{%user}}', 'username_windows');

        // table news_comment
        $this->createTable('{{%news_comment}}', [
            'id' => $this->primaryKey(),
            'id_news' => $this->integer()->notNull(),
            'comment' => $this->string('max')->notNull(),
            'username' => $this->string(250)->notNull(),
            'ip_address' => $this->string(50),
            'hostname' => $this->string(250),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'date_delete' => $this->dateTime(),
        ]);
        $this->addForeignKey('fk__news_comment__id_news____news', '{{%news_comment}}', 'id_news', '{{%news}}', 'id', 'cascade');
        $this->execute('
            create trigger tr_aiu__news_comment on {{%news_comment}} after insert, update, delete
            as
            begin
                if exists(select 1 from inserted)
                begin
                    update {{%news}} 
                        set count_comment = (select count(id) from {{%news_comment}} where id_news in (select id_news from inserted) and date_delete is null)
                    where id in (select id_news from inserted)
                end else
                begin
                    update {{%news}} 
                        set count_comment = (select count(id) from {{%news_comment}} where id_news in (select id_news from deleted) and date_delete is null)
                    where id in (select id_news from deleted)
                end
            end    
        ');

        // table news_like
        $this->createTable('{{%news_like}}', [
            'id' => $this->primaryKey(),
            'id_news' => $this->integer()->notNull(),
            'username' => $this->string(250)->notNull(),
            'ip_address' => $this->string(50),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
        ]);
        $this->addForeignKey('fk__news_like__id_news____news', '{{%news_like}}', 'id_news', '{{%news}}', 'id', 'cascade');
        $this->execute('
            create trigger tr_aiu__news_like on {{%news_like}} after insert, delete
            as
            begin
                if exists(select 1 from inserted)
                begin
                    update {{%news}} 
                        set count_like = (select count(id) from {{%news_like}} where id_news in (select id_news from inserted))
                    where id in (select id_news from inserted)
                end else
                begin
                    update {{%news}} 
                        set count_like = (select count(id) from {{%news_like}} where id_news in (select id_news from deleted))
                    where id in (select id_news from deleted)
                end
            end    
        ');

        // table news_visit
        $this->createTable('{{%news_visit}}', [
            'id' => $this->primaryKey(),
            'id_news' => $this->integer()->notNull(),
            'username' => $this->string(250)->notNull(),
            'ip_address' => $this->string(50),
            'hostname' => $this->string(50),
            'session_id' => $this->string(100),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
        ]);
        $this->addForeignKey('fk__news_visit__id_news____news', '{{%news_visit}}', 'id_news', '{{%news}}', 'id', 'cascade');
        $this->execute('
            create trigger tr_aiu__news_visit on {{%news_visit}} after insert, delete
            as
            begin
                if exists(select 1 from inserted)
                begin
                    update {{%news}} 
                        set count_visit = (select count(id) from {{%news_visit}} where id_news in (select id_news from inserted))
                    where id in (select id_news from inserted)
                end else
                begin
                    update {{%news}} 
                        set count_visit = (select count(id) from {{%news_visit}} where id_news in (select id_news from deleted))
                    where id in (select id_news from deleted)
                end
            end    
        ');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drop news_visit
        $this->execute('drop trigger tr_aiu__news_visit');
        $this->dropForeignKey('fk__news_visit__id_news____news', '{{%news_visit}}');
        $this->dropTable('{{%news_visit}}');

        // drop news_like
        $this->execute('drop trigger tr_aiu__news_like');
        $this->dropForeignKey('fk__news_like__id_news____news', '{{%news_like}}');
        $this->dropTable('{{%news_like}}');

        // drop news_comment
        $this->execute('drop trigger tr_aiu__news_comment');
        $this->dropForeignKey('fk__news_comment__id_news____news', '{{%news_comment}}');
        $this->dropTable('{{%news_comment}}');

        // drop news
        $this->dropForeignKey('fk__news__id_tree____tree', '{{%news}}');
        $this->dropForeignKey('fk__news__id_organization____organization', '{{%news}}');
        $this->dropForeignKey('fk__news__author____user', '{{%news}}');
        $this->dropTable('{{%news}}');
    }

}
