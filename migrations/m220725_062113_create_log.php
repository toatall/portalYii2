<?php

use yii\db\Migration;

/**
 * Class m220725_062113_create_log
 */
class m220725_062113_create_log extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%history}}', [
            'id' => $this->primaryKey(),
            'url' => $this->string(400)->notNull(),
            'title' => $this->string(2000),
            'count_visits' => $this->integer()->notNull(),
            'date' => $this->date()->notNull(),
        ]);
        $this->createIndex('index__history__url', '{{%history}}', ['url', 'date'], true);
        
        
        $this->createTable('{{%history_detail}}', [
            'id' => $this->primaryKey(),
            'id_history' => $this->integer()->notNull(),
            'is_ajax' => $this->boolean(),
            'is_pjax' => $this->boolean(),            
            'method' => $this->string(30),
            'host' => $this->string(40),
            'ip' => $this->string(15),
            'date_create' => $this->integer()->notNull(),
            'author' => $this->string(250)->notNull(),
            'author_org_code' => $this->string(5),
        ]);

        $this->execute("
            CREATE TRIGGER tr_history_detail ON  {{%history_detail}}
                AFTER INSERT,DELETE
            AS
            BEGIN
                DECLARE @id INT
            
                IF EXISTS(SELECT 1 FROM inserted)
                BEGIN
                    SELECT TOP 1 @id = [[id_history]] FROM inserted
                END ELSE
                BEGIN
                    SELECT TOP 1 @id = [[id_history]] FROM deleted
                END
                    
                UPDATE {{%history}}
                    SET [[count_visits]] = (SELECT COUNT(*) FROM {{%history_detail}} WHERE [[id_history]] = @id)
                WHERE [id] = @id
            END
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%history_detail}}');
        $this->dropTable('{{%history}}');
    }

}
