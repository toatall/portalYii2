<?php

use yii\db\Migration;

/**
 * Class m211210_115650_add_column_sort_test
 */
class m211210_115650_add_column_sort_test extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%test}}', 'use_formula_filter', $this->string(500));      
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%test}}', 'use_formula_filter');
    }

}
