<?php

use yii\db\Migration;

/**
 * Class m201009_115012_create_table_rating
 */
class m201009_115012_create_table_rating extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // table rating_main
        $this->createTable('{{%rating_main}}', [
            'id' => $this->primaryKey(),
            'id_tree' => $this->integer()->notNull(),
            'name' => $this->string(200)->notNull(),
            'order_asc' => $this->boolean(),
            'note' => $this->string('max'),
            'log_change' => $this->string('max'),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'author' => $this->string(250)->notNull(),
        ]);
        $this->addForeignKey('fk__rating_main__author____user', '{{%rating_main}}', 'author', '{{%user}}', 'username_windows');
        $this->addForeignKey('fk__rating_main__id_tree____tree', '{{%rating_main}}', 'id_tree', '{{%tree}}', 'id');

        // table rating_data
        $this->createTable('{{%rating_data}}', [
            'id' => $this->primaryKey(),
            'id_rating_main' => $this->integer()->notNull(),
            'note' => $this->string('max'),
            'rating_year' => $this->smallInteger()->notNull(),
            'rating_period' => $this->string(200)->notNull(),
            'log_change' => $this->string('max'),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'author' => $this->string(250)->notNull(),
        ]);
        $this->addForeignKey('fk__rating_data__id_rating_main____rating_main', '{{%rating_data}}', 'id_rating_main',
            '{{%rating_main}}', 'id');
        $this->addForeignKey('fk__rating_data__author____user', '{{%rating_data}}', 'author', '{{%user}}', 'username_windows');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drop rating_data
        $this->dropForeignKey('fk__rating_data__author____user', '{{%rating_data}}');
        $this->dropForeignKey('fk__rating_data__id_rating_main____rating_main', '{{%rating_data}}');
        $this->dropTable('{{%rating_data}}');

        // drop rating_main
        $this->dropForeignKey('fk__rating_main__author____user', '{{%rating_main}}');
        $this->dropForeignKey('fk__rating_main__id_tree____tree', '{{%rating_main}}');
        $this->dropTable('{{%rating_main}}');
    }

}
