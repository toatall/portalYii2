<?php

use yii\db\Migration;

/**
 * Class m231010_062136_table_automation_routine
 */
class m231010_062136_table_automation_routine extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%automation_routine}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(300)->notNull(),
            'description' => $this->text(),           
            'owners' => $this->string(250)->notNull(), 
            'ftp_path' => $this->string(250), 
            'author' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime()->notNull(),
            'date_update' => $this->dateTime(),
        ]);

        $this->createTable('{{%automation_routine_downloads}}', [
            'id' => $this->primaryKey(),
            'id_automation_routine' => $this->integer()->notNull(),
            'filename' => $this->string(500)->notNull(),
            'author' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime()->notNull(),
        ]);
        $this->addForeignKey('fk__automation_routine_downloads__id_automation_route', '{{%automation_routine_downloads}}', 'id_automation_routine',
            '{{%automation_routine}}', 'id', 'cascade');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk__automation_routine_downloads__id_automation_route', '{{%automation_routine_downloads}}');
        $this->dropTable('{{%automation_routine_downloads}}');

        $this->dropTable('{{%automation_routine}}');
    }

}
