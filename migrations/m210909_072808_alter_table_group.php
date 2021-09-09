<?php

use yii\db\Migration;

/**
 * Class m210909_072808_alter_table_group
 */
class m210909_072808_alter_table_group extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%group}}', 'is_global', $this->boolean());
        $this->update('{{%group}}', [
            'is_global' => 0,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%group}}', 'is_global');
    }

}
