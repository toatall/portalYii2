<?php

use yii\db\Migration;

/**
 * Class m201009_160218_create_table_reg_ecr
 */
class m201009_160218_create_table_reg_ecr extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // reg_ecr
        $this->createTable('{{%reg_ecr}}', [
            'id' => $this->primaryKey(),
            'code_org' => $this->string(5)->notNull(),
            'date_reg' => $this->date()->notNull(),
            'count_create' => $this->integer()->notNull(),
            'count_vote' => $this->integer()->notNull(),
            'avg_eval_a_1_1' => $this->integer()->notNull(),
            'avg_eval_a_1_2' => $this->integer()->notNull(),
            'avg_eval_a_1_3' => $this->integer()->notNull(),
            'author' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'date_update' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'date_delete' => $this->dateTime(),
        ]);
        $this->addForeignKey('fk__reg_ecr__code_org____organization', '{{%reg_ecr}}',
            'code_org', '{{%organization}}', 'code');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drop reg_ecr
        $this->dropForeignKey('fk__reg_ecr__code_org____organization', '{{%reg_ecr}}');
        $this->dropTable('{{%reg_ecr}}');
    }

}
