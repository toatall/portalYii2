<?php

use yii\db\Migration;

/**
 * Class m201009_121141_create_table_zg
 */
class m201009_121141_create_table_zg extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // table zg_template
        $this->createTable('{{%zg_template}}', [
            'id' => $this->primaryKey(),
            'kind' => $this->string(1000)->notNull(),
            'description' => $this->string('max'),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'date_update' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
            'author' => $this->string(250)->notNull(),
        ]);
        $this->addForeignKey('fk__zg_template__author____user', '{{%zg_template}}', 'author', '{{%user}}', 'username_windows');

        // zg_template_file
        $this->createTable('{{%zg_template_file}}', [
            'id' => $this->primaryKey(),
            'id_zg_template' => $this->integer()->notNull(),
            'filename' => $this->string('max'),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
        ]);
        $this->addForeignKey('fk__zg_template_file__id_zg_template____zg_template', '{{%zg_template_file}}',
            'id_zg_template', '{{%zg_template}}', 'id');

        // zg_template_kind
        $this->createTable('{{%zg_template_kind}}', [
            'kind_name' => $this->string(200)->notNull(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()')->notNull(),
        ]);
        $this->addPrimaryKey('pk__zg_template_kind__kind_name', '{{%zg_template_kind}}','kind_name');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // dtop zg_template_kind
        $this->dropPrimaryKey('pk__zg_template_kind__kind_name', '{{%zg_template_kind}}');
        $this->dropTable('{{%zg_template_kind}}');

        // drop zg_template_file
        $this->dropForeignKey('fk__zg_template_file__id_zg_template____zg_template', '{{%zg_template_file}}');
        $this->dropTable('{{%zg_template_file}}');

        // drop zg_template
        $this->dropForeignKey('fk__zg_template__author____user', '{{%zg_template}}');
        $this->dropTable('{{%zg_template}}');
    }


}

