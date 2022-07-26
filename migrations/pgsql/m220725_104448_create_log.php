<?php

use yii\db\Migration;

/**
 * Class m220725_104448_create_log
 */
class m220725_104448_create_log extends Migration
{
    
    public function init() 
    {        
        $this->db = 'dbPgsqlLog';
        parent::init();
    }
       
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%log}}', [
            'id' => $this->primaryKey(),
            'level' => $this->integer(),
            'category' => $this->string(255),
            'url' => $this->string(1000)->null(),
            'statusCode' => $this->string(30)->null(),
            'statusText' => $this->text()->null(),
            'user' => $this->string(255),
            'log_time' => $this->float(),
            'prefix' => $this->text(),
            'message' => $this->text(),
        ]);
        $this->createIndex('idx__log__level', '{{%log}}', 'level');
        $this->createIndex('idx__log__category', '{{%log}}', 'category');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%log}}');
    }
    
}
