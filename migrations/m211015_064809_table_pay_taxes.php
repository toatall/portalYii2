<?php

use yii\db\Migration;

/**
 * Class m211015_064809_table_pay_taxes
 */
class m211015_064809_table_pay_taxes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // general
        $this->createTable('{{%pay_taxes_general}}', [
            'id' => $this->primaryKey(),
            'code_org' => $this->string(5)->notNull(),
            'date' => $this->date()->notNull(),
            'sum1' => $this->float(),
            'sum2' => $this->float(),
            'sum3' => $this->float(),
            'sms' => $this->float(),
            'date_create' => $this->dateTime(),
        ]);
        $this->addForeignKey('fk__pay_taxes_general__code_org', '{{%pay_taxes_general}}', 'code_org', '{{%organization}}', 'code');

        // chart by month
        $this->createTable('{{%pay_taxes_chart_month}}', [
            'id' => $this->primaryKey(),
            'code_org' => $this->string(5)->notNull(),
            'month' => $this->string(50)->notNull(),
            'sum1' => $this->float(),
            'date_create' => $this->dateTime(),
        ]);
        $this->addForeignKey('fk__pay_taxes_chart_month__code_org', '{{%pay_taxes_chart_month}}', 'code_org', '{{%organization}}', 'code');

        // chart by day
        $this->createTable('{{%pay_taxes_chart_day}}', [
            'id' => $this->primaryKey(),
            'code_org' => $this->string(5)->notNull(),
            'date' => $this->date()->notNull(),
            'sum1' => $this->float(),
            'date_create' => $this->dateTime(),
        ]);
        $this->addForeignKey('fk__pay_taxes_chart_day__code_org', '{{%pay_taxes_chart_day}}', 'code_org', '{{%organization}}', 'code');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // chart month
        $this->dropForeignKey('fk__pay_taxes_chart_month__code_org', '{{%pay_taxes_chart_month}}');
        $this->dropTable('{{%pay_taxes_chart_month}}');

        // chart day
        $this->dropForeignKey('fk__pay_taxes_chart_day__code_org', '{{%pay_taxes_chart_day}}');
        $this->dropTable('{{%pay_taxes_chart_day}}');

        // general
        $this->dropForeignKey('fk__pay_taxes_general__code_org', '{{%pay_taxes_general}}');
        $this->dropTable('{{%pay_taxes_general}}');
    }

    
}
