<?php

use yii\db\Migration;

/**
 * Class m211011_105536_create_table_contest_hr
 */
class m211011_105536_create_table_contest_hr extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%contest_hr_people}}', [
            'id' => $this->primaryKey(),
            'fio' => $this->string(500)->notNull(),
            'photo' => $this->string(1000)->notNull(),
            'date_create' => $this->dateTime()->notNull(),
        ]);

        // craete
        $this->createTable('{{%contest_hr_result}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime()->notNull(),
        ]);
        $this->createTable('{{%contest_hr_result_data}}', [
            'id' => $this->primaryKey(),
            'id_hr_result' => $this->integer()->notNull(),
            'id_hr_people' => $this->integer()->notNull(),
            'temperature' => $this->string(10)->notNull(),
            'temperature_user' => $this->string(10),
        ]); 
        $this->addForeignKey('fk-contest_hr_result_data-id_hr_result', '{{%contest_hr_result_data}}', 'id_hr_result', '{{%contest_hr_result}}', 'id', 'cascade');
        $this->addForeignKey('fk-contest_hr_result_data-id_hr_people', '{{%contest_hr_result_data}}', 'id_hr_people', '{{%contest_hr_people}}', 'id', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-contest_hr_result_data-id_hr_people', '{{%contest_hr_result_data}}');
        $this->dropForeignKey('fk-contest_hr_result_data-id_hr_result', '{{%contest_hr_result_data}}');
        $this->dropTable('{{%contest_hr_result_data}}');
        $this->dropTable('{{%contest_hr_result}}');

        $this->dropTable('{{%contest_hr_people}}');
    }
    
}
