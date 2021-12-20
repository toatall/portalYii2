<?php

use yii\db\Migration;

/**
 * Class m211216_072428_creat_table_telephones
 */
class m211216_072428_creat_table_telephones extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%telephone_department}}', [
            'unid' => $this->string(32)->notNull()->unique(),
            'unid_parent' => $this->string(32),
            'form' => $this->string(30)->notNull(), // "Department" | "Organization"
            'org_code' => $this->string(5), // Organization
            'index' => $this->string(10), // Department
            'name' => $this->string('max'),
            'full_name' => $this->string('max'),
            'leader' => $this->string(500),
            'phone' => $this->string(300),
            'fax' => $this->string(300),
            'address' => $this->string(300),
            'mail' => $this->string(300),
            'date_create' => $this->dateTime()->notNull(),
            'date_update' => $this->dateTime()->notNull(),
            'author' => $this->string(250),
        ]);
        $this->addPrimaryKey('pk__telephone_department__unid', '{{%telephone_department}}', 'unid');

        $this->createTable('{{%telephone_user}}', [
            'unid' => $this->string(32)->notNull()->unique(),
            'unid_department' => $this->string(32)->notNull(),
            'fio' => $this->string(500)->notNull(),
            'telephone' => $this->string(300),
            'telephone_dop' => $this->string(300),
            'location' => $this->string(100),
            'mail' => $this->string(300),
            'department_name' => $this->string('max'),
            'post' => $this->string(200),
            'notes_name' => $this->string(300),
            'index' => $this->string(50),
            'date_create' => $this->dateTime()->notNull(),
            'date_update' => $this->dateTime()->notNull(),
            'author' => $this->string(250),
        ]);
        $this->addPrimaryKey('pk__telephone_user__unid', '{{%telephone_user}}', 'unid');

        $this->createTable('{{%telephone_update}}', [
            'id' => $this->primaryKey(),
            'date' => $this->dateTime()->notNull(),
            'result' => $this->string('max'),
            'status' => $this->integer()->notNull(),
        ]);

        // buffer
        $this->createTable('{{%telephone_department_buffer}}', [
            'unid' => $this->string(32)->notNull()->unique(),
            'unid_parent' => $this->string(32),
            'form' => $this->string(30)->notNull(), // "Department" | "Organization"
            'org_code' => $this->string(5), // Organization
            'index' => $this->string(10), // Department
            'name' => $this->string('max'),
            'full_name' => $this->string('max'),
            'leader' => $this->string(500),
            'phone' => $this->string(300),
            'fax' => $this->string(300),
            'address' => $this->string(300),
            'mail' => $this->string(300),
            'date_create' => $this->dateTime()->notNull(),
            'date_update' => $this->dateTime()->notNull(),
            'author' => $this->string(250),
        ]);
        $this->addPrimaryKey('pk__telephone_department_buffer__unid', '{{%telephone_department_buffer}}', 'unid');

        $this->createTable('{{%telephone_user_buffer}}', [
            'unid' => $this->string(32)->notNull()->unique(),
            'unid_department' => $this->string(32)->notNull(),
            'fio' => $this->string(500)->notNull(),
            'telephone' => $this->string(300),
            'telephone_dop' => $this->string(300),
            'location' => $this->string(100),
            'mail' => $this->string(300),
            'department_name' => $this->string('max'),
            'post' => $this->string(200),
            'notes_name' => $this->string(300),
            'index' => $this->string(50),
            'date_create' => $this->dateTime()->notNull(),
            'date_update' => $this->dateTime()->notNull(),
            'author' => $this->string(250),
        ]);
        $this->addPrimaryKey('pk__telephone_user_buffer__unid', '{{%telephone_user_buffer}}', 'unid');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%telephone_user_buffer}}');
        $this->dropTable('{{%telephone_department_buffer}}');

        $this->dropTable('{{%telephone_update}}');
        $this->dropTable('{{%telephone_user}}');
        $this->dropTable('{{%telephone_department}}');
    }

}
