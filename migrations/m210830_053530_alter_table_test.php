<?php

use yii\db\Migration;

/**
 * Class m210830_053530_alter_table_test
 * Показывать правильные ответы во время сдачи теста и в результате
 */
class m210830_053530_alter_table_test extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%test}}', 'show_right_answer', $this->boolean());
        $this->addColumn('{{%test}}', 'finish_text', $this->string(2500));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%test}}', 'show_right_answer');
        $this->dropColumn('{{%test}}', 'finish_text');
    }

}
