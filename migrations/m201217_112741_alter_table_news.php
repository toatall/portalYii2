<?php

use yii\db\Migration;

/**
 * Class m201217_112741_alter_table_news
 */
class m201217_112741_alter_table_news extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%news}}', 'from_department',  $this->string(500));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%news}}', 'from_department');
    }

}
