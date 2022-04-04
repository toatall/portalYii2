<?php

use yii\db\Migration;

/**
 * Class m220317_041156_alter_table_contest_map
 */
class m220317_041156_alter_table_contest_map extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%contest_map}}', 'fio_home_place', $this->string('max'));
        $this->addColumn('{{%contest_map}}', 'note', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%contest_map}}', 'fio_home_place');
        $this->dropColumn('{{%contest_map}}', 'note');
    }

    
}
