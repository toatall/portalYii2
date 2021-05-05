<?php

use yii\db\Migration;

/**
 * Class m210405_110914_alter_table_arts
 */
class m210405_110914_alter_table_arts extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%contest_arts}}', 'date_show_2', $this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%contest_arts}}', 'date_show_2');
    }

}
