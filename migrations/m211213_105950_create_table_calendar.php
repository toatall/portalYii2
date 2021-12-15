<?php

use yii\db\Migration;

/**
 * Class m211213_105950_create_table_calendar
 */
class m211213_105950_create_table_calendar extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%calendar}}', [
            'id' => $this->primaryKey(),
            'date' => $this->date()->notNull(),            
            'org_code' => $this->string(5)->notNull(),
            'color' => $this->string(50), 
            'type_text' => $this->string(50)->notNull(),           
            'description' => $this->text()->notNull(),
            'is_global' => $this->boolean()->notNull(), 
            'sort' => $this->integer(),
            'date_create' => $this->dateTime()->notNull(),
            'date_delete' => $this->dateTime(),
            'date_update' => $this->dateTime(),
            'author' => $this->string(250)->notNull(),
            'log_change' => $this->string('max'),
        ]);
        // $this->createIndex('index__calendar__date', '{{%calendar}}', ['date', 'org_code'], true);
        $this->addForeignKey('fk__calendar__organization', '{{%calendar}}', 'org_code', '{{%organization}}', 'code');
        $this->addForeignKey('fk__calendar__user', '{{%calendar}}', 'author', '{{%user}}', 'username');

        $this->createTable('{{%calendar_types}}', [
            'id' => $this->primaryKey(),
            'type_text' => $this->string(50)->notNull()->unique(),
            'date_create' => $this->dateTime()->notNull(),
            'date_update' => $this->dateTime()->notNull(),
            'author' => $this->string(250)->notNull(),
        ]);    
        $this->addForeignKey('fk__calendar_types__user', '{{%calendar_types}}', 'author', '{{%user}}', 'username');

        $this->createTable('{{%calendar_color}}', [
            'id' => $this->primaryKey(),
            'date' => $this->date()->notNull(),            
            'org_code' => $this->string(5)->notNull(),
            'color' => $this->string(50), 
            'date_create' => $this->dateTime()->notNull(),
            'date_update' => $this->dateTime(),
            'author' => $this->string(250)->notNull(),
        ]);
        $this->createIndex('index__calendar_color__date', '{{%calendar_color}}', ['date', 'org_code'], true);
        $this->addForeignKey('fk__calendar_color__user', '{{%calendar_color}}', 'author', '{{%user}}', 'username');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk__calendar_color__user', '{{%calendar_color}}');
        $this->dropTable('{{%calendar_color}}');

        $this->dropForeignKey('fk__calendar_types__user', '{{%calendar_types}}');
        $this->dropTable('{{%calendar_types}}');

        $this->dropForeignKey('fk__calendar__organization', '{{%calendar}}');
        $this->dropForeignKey('fk__calendar__user', '{{%calendar}}');
        $this->dropTable('{{%calendar}}');
    }

    
}
