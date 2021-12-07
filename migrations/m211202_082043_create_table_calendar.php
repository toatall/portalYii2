<?php

use yii\db\Migration;

/**
 * Class m211202_082043_create_table_calendar
 */
class m211202_082043_create_table_calendar extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%calendar}}', [
            'id' => $this->primaryKey(),
            'date' => $this->date()->notNull(),            
            'code_org' => $this->string(5)->notNull(),
            'color' => $this->string(50),                                              
            'date_create' => $this->dateTime()->notNull(),
            'date_delete' => $this->dateTime(),
            'date_update' => $this->dateTime(),
            'author' => $this->string(250)->notNull(),
            'log_change' => $this->string('max'),
        ]);
        $this->createIndex('index__calendar__date', '{{%calendar}}', ['date', 'code_org'], true);
        $this->addForeignKey('fk__calendar__organization', '{{%calendar}}', 'code_org', '{{%organization}}', 'code');
        $this->addForeignKey('fk__calendar__user', '{{%calendar}}', 'author', '{{%user}}', 'username');

        $this->createTable('{{%calendar_types}}', [
            'id' => $this->primaryKey(),
            'type_text' => $this->string(50)->notNull()->unique(),
            'date_create' => $this->dateTime()->notNull(),
            'date_update' => $this->dateTime()->notNull(),
            'author' => $this->string(250)->notNull(),
        ]);        

        $this->createTable('{{%calendar_data}}', [
            'id' => $this->primaryKey(),
            'id_calendar' => $this->integer()->notNull(),
            'type_text' => $this->string(50)->notNull(),
            'color' => $this->string(50),
            'description' => $this->text()->notNull(),
            'is_global' => $this->boolean()->notNull(), 
            'sort' => $this->integer(),
            'date_create' => $this->dateTime()->notNull(),
            'date_update' => $this->dateTime()->notNull(),
            'author' => $this->string(250)->notNull(),
        ]);        
        $this->addForeignKey('fk__calendar__calendar_data', '{{%calendar_data}}', 'id_calendar', '{{%calendar}}', 'id');        
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk__calendar__calendar_data', '{{%calendar_data}}');
        $this->dropTable('{{%calendar_data}}');

        $this->dropTable('{{%calendar_types}}');

        $this->dropForeignKey('fk__calendar__organization', '{{%calendar}}');
        $this->dropForeignKey('fk__calendar__user', '{{%calendar}}');
        $this->dropTable('{{%calendar}}');
    }

}
