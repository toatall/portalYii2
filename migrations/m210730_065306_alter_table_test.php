<?php

use yii\db\Migration;

/**
 * Class m210730_065306_alter_table_test
 */
class m210730_065306_alter_table_test extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%test_result}}', 'status', $this->integer());
        $this->addColumn('{{%test_result}}', 'seconds', $this->integer());                  
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {        
        $this->dropColumn('{{%test_result}}', 'status');
        $this->dropColumn('{{%test_result}}', 'seconds');        
    }
   
}
