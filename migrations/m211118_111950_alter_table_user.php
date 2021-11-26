<?php

use yii\db\Migration;

/**
 * Class m211118_111950_alter_table_user
 */
class m211118_111950_alter_table_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'mail_ad', $this->string(250)); // почтовый ящик, указанный в ActiveDirectory
        $this->addColumn('{{%user}}', 'room_name_ad', $this->string(50)); // кабинет, указанный в ActiveDirectory
        $this->addColumn('{{%user}}', 'user_disabled_ad', $this->boolean()); // статус, указанный в ActiveDirectory
        $this->addColumn('{{%user}}', 'date_update_ad', $this->dateTime()); // дата и время обновления из ActiveDirectory
        $this->addColumn('{{%user}}', 'description_ad', $this->string('max')); // описание из ActiveDirectory
        $this->addColumn('{{%user}}', 'photo_file', $this->string(500)); // аватар
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'mail_ad');
        $this->dropColumn('{{%user}}', 'room_name_ad');
        $this->dropColumn('{{%user}}', 'user_disabled_ad');
        $this->dropColumn('{{%user}}', 'date_update_ad');
        $this->dropColumn('{{%user}}', 'description_ad');
        $this->dropColumn('{{%user}}', 'photo_file');
    }
   
}
