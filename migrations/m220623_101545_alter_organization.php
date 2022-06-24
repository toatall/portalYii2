<?php

use yii\db\Migration;

/**
 * Class m220623_101545_alter_organization
 */
class m220623_101545_alter_organization extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%organization}}', 'description', $this->text());        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%organization}}', 'description');
    }

}
