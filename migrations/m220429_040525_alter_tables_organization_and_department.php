<?php

use yii\db\Migration;

/**
 * Class m220429_040525_alter_tables_organization_and_department
 */
class m220429_040525_alter_tables_organization_and_department extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%department}}', 'short_name', $this->string(50));
        $this->addColumn('{{%organization}}', 'short_name', $this->string(50));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%department}}', 'short_name');
        $this->dropColumn('{{%organization}}', 'short_name');
    }
    
}
