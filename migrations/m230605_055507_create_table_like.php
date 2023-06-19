<?php

use yii\db\Migration;

/**
 * Class m230605_055507_create_table_like
 */
class m230605_055507_create_table_like extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%like}}', [
            'id' => $this->primaryKey(),
            'unique' => $this->string(250)->notNull()->unique(),            
            'count' => $this->integer(),
            'filter_allow' => $this->string(2000),
        ]);

        $this->createTable('{{%like_data}}', [
            'id' => $this->primaryKey(),
            'id_like' => $this->integer(),
            'username' => $this->string(250)->notNull(),
            'date_create' => $this->integer()->notNull(),
        ]);      

        $this->addForeignKey('fk__like_data__id_like', '{{%like_data}}', 'id_like',
            '{{%like}}', 'id', 'cascade');
        $this->addForeignKey('fk__like_data__username', '{{%like_data}}', 'username',
            '{{%user}}', 'username');        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk__like_data__id_like', '{{%like_data}}');
        $this->dropForeignKey('fk__like_data__username', '{{%like_data}}');

        $this->dropTable('{{%like_data}}');
        $this->dropTable('{{%like}}');
    }

}
