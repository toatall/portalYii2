<?php

use yii\db\Migration;

/**
 * Class m231004_092547_contest_pets_like
 */
class m231004_092547_contest_pets_like extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%contest_pets_like}}', [
            'id' => $this->primaryKey(),
            'id_contest_pets' => $this->integer()->notNull(),
            'username' => $this->string(250)->notNull(),
            'date_crate' => $this->dateTime(),
        ]);
        $this->addForeignKey('fk__contest_pets_like__id_contest_pets', '{{%contest_pets_like}}', 'id_contest_pets',
            '{{%contest_pets}}', 'id', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk__contest_pets_like__id_contest_pets', '{{%contest_pets_like}}');
        $this->dropTable('{{%contest_pets_like}}');
    }
   
}
