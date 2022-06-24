<?php

use yii\db\Migration;

/**
 * Class m220624_045940_alter_department
 */
class m220624_045940_alter_department extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk__department__id_tree____tree', '{{%department}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addForeignKey('fk__department__id_tree____tree', '{{%department}}', 'id_tree', '{{%tree}}', 'id');
    }
   
}
