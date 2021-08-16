<?php

use yii\db\Migration;

/**
 * Class m210813_122518_create_table_photohunter
 */
class m210813_122518_create_table_photohunter extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // фотографии для конкурса
        $this->createTable('{{%rookie_photohunter_photos}}', [
            'id' => $this->primaryKey(),
            'code_no' => $this->string(5)->notNull(),
            'id_department' => $this->integer(),
            'image' => $this->string(500)->notNull(),
            'thumb' => $this->string(500)->notNull(),
            'nomination' => $this->string(250),
            'title' => $this->string('max'),
            'description' => $this->string('max'),
            'date_create' => $this->dateTime(),
        ]);

        // голосование по фотографиям
        $this->createTable('{{%rookie_photohunter_photos_votes}}', [
            'id' => $this->primaryKey(),
            'id_photos' => $this->integer()->notNull(),            
            'mark_creative' => $this->smallInteger()->notNull(),
            'mark_art' => $this->smallInteger()->notNull(),
            'mark_original' => $this->smallInteger()->notNull(),
            'mark_accordance' => $this->smallInteger()->notNull(),
            'mark_quality' => $this->smallInteger()->notNull(),
            'username' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime(),
        ]);
        $this->addForeignKey('fk-rookie_photohunter_photos_votes-id_photos', '{{%rookie_photohunter_photos_votes}}', 'id_photos', '{{%rookie_photohunter_photos}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-rookie_photohunter_photos_votes-id_photos', '{{%rookie_photohunter_photos_votes}}');
        $this->dropTable('{{%rookie_photohunter_photos_votes}}');
       
        $this->dropTable('{{%rookie_photohunter_photos}}');
    }
   
}
