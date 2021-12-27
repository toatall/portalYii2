<?php

use yii\db\Migration;

/**
 * Class m211224_060512_add_test_new_type
 */
class m211224_060512_add_test_new_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%test}}', 'user_input', $this->boolean());
        $this->addColumn('{{%test_question}}', 'input_answers', $this->string('max'));
        $this->addColumn('{{%test_result_question}}', 'input_answers', $this->string('max'));  
        $this->addColumn('{{%test_result}}', 'is_checked', $this->boolean());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%test}}', 'user_input');
        $this->dropColumn('{{%test_question}}', 'input_answers');
        $this->dropColumn('{{%test_result_question}}', 'input_answers');    
        $this->dropColumn('{{%test_result}}', 'is_checked');
    }

}
