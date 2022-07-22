<?php

use yii\db\Migration;

/**
 * Class m220722_095124_create_like_lifehack
 */
class m220722_095124_create_like_lifehack extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {   
        $this->createTable('{{%lifehack_like}}', [
            'id' => $this->primaryKey(),
            'id_lifehack' => $this->integer()->notNull(),
            'rate' => $this->integer()->notNull(),
            'username' => $this->string(250)->notNull(),
            'date_create' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('fk__lifehack_like__id_lifehack', '{{%lifehack_like}}', 'id_lifehack', 
            '{{%lifehack}}', 'id', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%lifehack_like}}');
    }
    
}
