<?php

use yii\db\Migration;

/**
 * Class m201110_104518_table_thirty_radio
 */
class m201110_104518_table_thirty_radio extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // thirty_radio
        $this->createTable('{{%thirty_radio}}', [
            'id' => $this->primaryKey(),
            'filename' => $this->string(500)->notNull(),
            'description' => $this->string('max'),
            'date_create' => $this->dateTime()->defaultExpression('getdate()'),
            'date_update' => $this->dateTime()->defaultExpression('getdate()'),
            'author' => $this->string(250)->notNull(),
            'count_comments' => $this->integer()->defaultValue(0),
            'count_view' => $this->integer()->defaultValue(0),
            'count_like' => $this->integer()->defaultValue(0),
        ]);

        // thirty_radio_comment
        $this->createTable('{{%thirty_radio_comment}}', [
            'id' => $this->primaryKey(),
            'id_radio' => $this->integer()->notNull(),
            'comment' => $this->string('max')->notNull(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()'),
            'date_update' => $this->dateTime()->defaultExpression('getdate()'),
            'author' => $this->string(250)->notNull(),
            'date_delete' => $this->dateTime(),
        ]);
        $this->addForeignKey('fk__thirty_radio_comment__id_radio____thirty_radio', '{{%thirty_radio_comment}}', 'id_radio',
            '{{%thirty_radio}}', 'id', 'cascade');

        // thirty_radio_like
        $this->createTable('{{%thirty_radio_like}}', [
            'id' => $this->primaryKey(),
            'id_radio' => $this->integer()->notNull(),
            'username' => $this->string(250)->notNull(),
            'ip_address' => $this->string(50),
            'date_create' => $this->dateTime()->defaultExpression('getdate()'),
        ]);
        $this->addForeignKey('fk__thirty_radio_like__id_radio____thirty_radio', '{{%thirty_radio_like}}', 'id_radio',
            '{{%thirty_radio}}', 'id', 'cascade');

        // thirty_radio_visit
        $this->createTable('{{%thirty_radio_visit}}', [
            'id' => $this->primaryKey(),
            'id_radio' => $this->integer()->notNull(),
            'username' => $this->string(250)->notNull(),
            'ip_address' => $this->string(50),
            'date_create' => $this->dateTime()->defaultExpression('getdate()'),
        ]);
        $this->addForeignKey('fk__thirty_radio_visit__id_radio____thirty_radio', '{{%thirty_radio_visit}}', 'id_radio',
            '{{%thirty_radio}}', 'id', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // thirty_radio_visit
        $this->dropForeignKey('fk__thirty_radio_visit__id_radio____thirty_radio', '{{%thirty_radio_visit}}');
        $this->dropTable('{{%thirty_radio_visit}}');

        // thirty_radio_like
        $this->dropForeignKey('fk__thirty_radio_like__id_radio____thirty_radio', '{{%thirty_radio_like}}');
        $this->dropTable('{{%thirty_radio_like}}');

        // thirty_radio_comment
        $this->dropForeignKey('fk__thirty_radio_comment__id_radio____thirty_radio', '{{%thirty_radio_comment}}');
        $this->dropTable('{{%thirty_radio_comment}}');

        // thirty_radio
        $this->dropTable('{{%thirty_radio}}');
    }

}
