<?php

use yii\db\Migration;

/**
 * Class m211021_140315_organization_add_short_name
 */
class m211021_140315_organization_add_short_name extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%organization}}', 'name_short', $this->string(200));
        $this->addColumn('{{%organization}}', 'date_end', $this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%organization}}', 'name_short');
        $this->dropColumn('{{%organization}}', 'date_end');
    }
    
}
