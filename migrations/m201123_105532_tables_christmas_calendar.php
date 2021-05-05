<?php

use yii\db\Migration;

/**
 * Class m201123_105532_tables_cristmas_calendar
 */
class m201123_105532_tables_christmas_calendar extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // все пользователи
        $this->createTable('{{%christmas_calendar_users}}', [
            'id' => $this->primaryKey(),
            'fio' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()'),
        ]);

        // что загадано в календаре
        $this->createTable('{{%christmas_calendar_question}}', [
            'id' => $this->primaryKey(),
            'id_user' => $this->integer()->notNull(),
            'day' => $this->integer()->notNull(),
            'photo' => $this->string(500),
            'description' => $this->string('max'),
            'date_create' => $this->dateTime()->defaultExpression('getdate()'),
        ]);
        $this->addForeignKey('fk__christmas_calendar_question__id_user____christmas_calendar_users', '{{%christmas_calendar_question}}', 'id_user',
            '{{%christmas_calendar_users}}', 'id', 'cascade');

        // что выбрано пользователем
        $this->createTable('{{%christmas_calendar_answer}}', [
            'id' => $this->primaryKey(),
            'id_question' => $this->integer()->notNull(),
            'id_user' => $this->integer()->notNull(),
            'username' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()'),
        ]);
        $this->addForeignKey('fk__christmas_calendar_answer__id_question____christmas_calendar_question', '{{%christmas_calendar_answer}}', 'id_question',
            '{{%christmas_calendar_question}}', 'id', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // что выбрано пользователем
        $this->dropForeignKey('fk__christmas_calendar_answer__id_question____christmas_calendar_question', '{{%christmas_calendar_answer}}');
        $this->dropTable('{{%christmas_calendar_answer}}');

        // что загадано в календаре
        $this->dropForeignKey('fk__christmas_calendar_question__id_user____christmas_calendar_users', '{{%christmas_calendar_question}}');
        $this->dropTable('{{%christmas_calendar_question}}');

        // все пользователи
        $this->dropTable('{{%christmas_calendar_users}}');
    }

}
