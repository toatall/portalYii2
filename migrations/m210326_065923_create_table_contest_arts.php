<?php

use yii\db\Migration;

/**
 * Class m210326_065923_create_table_contest_arts
 */
class m210326_065923_create_table_contest_arts extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // основаная таблица с картинами (оргинал и воспроизведенная)
        $this->createTable('{{%contest_arts}}', [
            'id' => $this->primaryKey(),
            'date_show' => $this->date()->notNull(),
            'department_name' => $this->string(300)->notNull(),
            'department_ad_group' => $this->string(300),
            'image_original' => $this->string(500)->notNull(),
            'image_original_author' => $this->string(300),
            'image_original_title' => $this->string(1000),
            'image_reproduced' => $this->string(500)->notNull(),
            'image_reproduced_title' => $this->string(1000),
            'description_original' => $this->string('max'),
            'description_reproduced' => $this->string('max'),
            'qr_code_file' => $this->string(500),
            'date_create' => $this->dateTime()->defaultExpression('getdate()'),
            'date_update' => $this->dateTime()->defaultExpression('getdate()'),
        ]);
        
        // таблица с ответами
        $this->createTable('{{%contest_arts_results}}', [
            'id' => $this->primaryKey(),
            'id_arts' => $this->integer()->notNull(),
            'author' => $this->string(250)->notNull(),
            'image_name' => $this->string(300)->notNull(),
            'image_author' => $this->string(300)->notNull(),
            'is_right' => $this->boolean(),
            'date_create' => $this->dateTime()->defaultExpression('getdate()'),
        ]);
        $this->addForeignKey('fk-contest_atrs_results-id_arts', '{{%contest_arts_results}}', 'id_arts', '{{%contest_arts}}', 'id', 'cascade');        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {        
        $this->dropForeignKey('fk-contest_atrs_results-id_arts', '{{%contest_arts_results}}');
        $this->dropTable('{{%contest_arts_results}}');
        
        $this->dropTable('{{%contest_arts}}');
    }
    
}
