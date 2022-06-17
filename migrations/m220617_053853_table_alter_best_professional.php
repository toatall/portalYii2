<?php

use yii\db\Migration;

/**
 * Class m220617_053853_table_alter_best_professional
 */
class m220617_053853_table_alter_best_professional extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%best_professional}}', 'nomination', $this->string(500));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%best_professional}}', 'nomination');
    }

}
