<?php

use yii\db\Migration;

/**
 * Class m220727_120405_create_user_log_authenticate
 */
class m220727_120405_create_user_log_authenticate extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_log_auth}}', [
            'session' => $this->string(30)->notNull(),            
            'browser_name' => $this->string(50),
            'browser_ver' => $this->string(15),
            'browser_maker' => $this->string(100),
            'platform' => $this->string(100),
            'platform_description' => $this->string(200),
            'platform_maker' => $this->string(100),
            'platform_bits' => $this->string(15),
            'screen_width' => $this->integer(),
            'screen_height' => $this->integer(),
            'agent_string' => $this->string('max'),
            'username' => $this->string(250)->notNull(),
            'date_create' =>$this->integer()->notNull(),
        ]);
        $this->addPrimaryKey('pk__user_log_auth__session', '{{%user_log_auth}}', 'session');
        $this->addForeignKey('fk__user_log_auth__username', '{{%user_log_auth}}', 
            'username', '{{%user}}', 'username');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_log_auth}}');
    }
   
}
