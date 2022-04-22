<?php

use yii\db\Migration;

/**
 * Class m220421_105701_best_professional
 */
class m220421_105701_best_professional extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%best_professional}}', [
            'id' => $this->primaryKey(),
            'org_code' => $this->string(5)->notNull(),
            'period' => $this->string(30)->notNull(),
            'period_year' => $this->smallInteger()->notNull(),
            'department' => $this->string(500)->notNull(),
            'fio' => $this->string(500)->notNull(),
            'description' => $this->text()->notNull(),
            'date_create' => $this->dateTime()->notNull(),
            'date_update' => $this->dateTime()->notNull(),
            'author' => $this->string(250)->notNull(),
            'log_change' => $this->text(),
        ]);
        $this->addForeignKey('fk__best_professional__author', '{{%best_professional}}', 'author', '{{%user}}', 'username'); 
        $this->addForeignKey('fk__best_professional__org_code', '{{%best_professional}}', 'org_code', '{{%organization}}', 'code'); 
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk__best_professional__org_code', '{{%best_professional}}');
        $this->dropForeignKey('fk__best_professional__author', '{{%best_professional}}');
        $this->dropTable('{{%best_professional}}');
    }

   
}
