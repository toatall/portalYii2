<?php

use yii\db\Migration;

/**
 * Class m221221_055436_create_awards
 */
class m221221_055436_create_awards extends Migration
{

    public function init()
    {
        $this->db = 'dbDKS';
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%awards}}', [
            'id' => 'uniqueidentifier NOT NULL default newid()',
            'org_code' => $this->string(5)->notNull(),
            'org_name' => $this->string('max')->notNull(),
            'fio' => $this->string('max')->notNull(),
            'dep_index' => $this->string(30),
            'dep_name' => $this->string('max'),
            'post' => $this->string('max'),
            'aw_name' => $this->string('max'),
            'aw_doc' => $this->string('max'),
            'aw_doc_num' => $this->string('max'),
            'aw_date_doc' => $this->datetime(),
            'date_create' => $this->dateTime(),
            'date_update' => $this->dateTime(),
            'flag_dks' => $this->boolean(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%awards}}');
    }
    
}
