<?php

use yii\db\Migration;

/**
 * Class m210903_115256_alter_table_conference
 */
class m210903_115256_alter_table_conference extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%conference}}', 'approve_author', $this->string(250));
        $this->addColumn('{{%conference}}', 'author', $this->string(250));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%conference}}', 'approve_author');
        $this->dropColumn('{{%conference}}', 'author');
    }

}
