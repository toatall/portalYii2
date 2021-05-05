<?php

use yii\db\Migration;

/**
 * Class m210304_060901_table_ldap
 */
class m210304_060901_alter_table_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'memberof', $this->string('max'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'memberof');
    }
    
}
