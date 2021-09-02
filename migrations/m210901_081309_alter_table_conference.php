<?php

use yii\db\Migration;

/**
 * Class m210901_081309_alter_table_conference
 */
class m210901_081309_alter_table_conference extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%conference}}', 'denied_text', $this->string(2000));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%conference}}', 'denied_text');
    }

}
